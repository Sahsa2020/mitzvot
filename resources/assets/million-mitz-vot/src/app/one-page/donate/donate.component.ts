import { Component, OnInit, trigger, state, style, transition, animate, keyframes, AfterViewChecked, ViewChild} from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { OnePageService } from '../one-page.service';
import { ProfileService } from '../profile/profile.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../authenticate.service';

import { SellBox } from '../../model/sell_box.type';
import { Donate } from '../../model/donate.type';
import { FooterComponent } from '../../shared/index';

declare var jQuery:any;


@Component({
  selector: 'app-donate',
  templateUrl: './donate.component.html',
  styleUrls: ['./donate.component.css']
})
export class DonateComponent implements OnInit {
    @ViewChild('pay_box_dialog') pay_box_dialog: any;

    public sellBoxes:SellBox[] = [];
    public curSellBox:SellBox;
    public buy_count:number = 1;
    public donateId: number = 0;
    public userBoxAmount: number = 0;
    public paySuccess:number;
    public selDonate:Donate;
    public USER_SIGNED_INFO = USER_SIGNED_INFO;
    public searchString: string = "";
    public searchFilter: string = "name";
    public searchCategory: number = 0;
    public curPage:number = 1;
    public totalCount:number = 0;
    public itemsPerPage:number = 5;
    public isSelectedAll: boolean = false;

    constructor(public lang: LanguageService, public router: Router, public authenticateService: AuthenticateService, public appState: StateService, public generalService: GeneralService, public onePageService:OnePageService, public route:ActivatedRoute, public profileService:ProfileService) {
      this.appState.set("one_page_menu_selected", 9);
      this.curSellBox = new SellBox();
      this.selDonate = new Donate();

      this.appState.setLoading(this.tr("LOADING_TEXT"));
      this.onePageService.getSellBoxes().subscribe(
       result => {
         if (result.success) {
             this.sellBoxes = result.data;
             this.curSellBox = this.sellBoxes[0];
         } else {
           this.appState.errorMessage = this.tr("GET_FAILED");
         }
         this.appState.closeLoading();
       });

      this.refreshDonate({page: this.curPage});
    }
    ngOnInit() {
      if (!jQuery.fancybox) {
              return;
      }

      jQuery(".fancybox-fast-view").fancybox();

      if (jQuery(".fancybox-button").size() > 0) {
          jQuery(".fancybox-button").fancybox({
              groupAttr: 'data-rel',
              prevEffect: 'none',
              nextEffect: 'none',
              closeBtn: true,
              helpers: {
                  title: {
                      type: 'inside'
                  }
              }
          });

          jQuery('.fancybox-video').fancybox({
              type: 'iframe'
          });
      }

      this.route.params.forEach((params: Params) => {
        this.paySuccess = params && params['pay_success'];
        console.log(params);
      });
      // this.initFancybox();
    }

    initFancybox(){
      if (!jQuery.fancybox) {
              return;
          }

          jQuery(".fancybox-fast-view").fancybox();

          if (jQuery(".fancybox-button").size() > 0) {
              jQuery(".fancybox-button").fancybox({
                  groupAttr: 'data-rel',
                  prevEffect: 'none',
                  nextEffect: 'none',
                  closeBtn: true,
                  helpers: {
                      title: {
                          type: 'inside'
                      }
                  }
              });

              jQuery('.fancybox-video').fancybox({
                  type: 'iframe'
              });
          }
    }

    ngOnDestroy() {

    }
    tr(tran: string): string
    {
      return this.lang.tr("sell." + tran);
    }

    // changeAmount(num:number){
    //   this.buy_count += num;
    //   if (this.buy_count == 0) {
    //     this.buy_count = 1;
    //   }
    // }

    selectBox(id:number){
      this.curSellBox = this.sellBoxes[id]
      for (let sb of this.sellBoxes) {
          if (sb.id == id) {
              this.curSellBox = sb;
              break;
          }
      }
    }

    // donateOverview(){
    //   this.selDonate = new Donate();
    //   for (let i = 0; i < this.profileService.donates.length; i++) {
    //       if (this.profileService.donates[i].id == this.donate_id) {
    //         this.selDonate = this.profileService.donates[i];
    //       }
    //   }
    // }

   showPayDlg(donate_id:number){
    this.selDonate = this.profileService.donates[donate_id];
    this.donateId = this.profileService.donates[donate_id].id;
    this.pay_box_dialog.show();
   }

   approve(index){
     let donate_id = this.profileService.donates[index].id;
     this.appState.setLoading('Loading ...');
     this.profileService.approveDonate(donate_id).subscribe(
      result => {
        if(result != true)
          this.appState.errorMessage = "Can't approve. Please check the site now.";
        else
          this.profileService.donates[index].del_flg = 0;
        this.appState.closeLoading();
      });
   }

   private handleKeyDown(event: any)
   {
     if (event.keyCode == 13)
     {
        this.search();
     }
   }

   search(){
     this.refreshDonate({page: this.curPage});
   }

   refreshDonate(event){
     this.appState.setLoading('Loading ...');
     this.profileService.getAllDonates((event.page - 1)*this.itemsPerPage, this.itemsPerPage, this.searchString, this.searchFilter, this.searchCategory).subscribe(
      result => {
        if(result != true)
          this.appState.errorMessage = 'Donates Load Error';
        this.totalCount = this.profileService.donate_count;
        this.appState.closeLoading();
      });
   }

   get donatesSelected(){
     let flag: boolean = true;
     if(this.profileService.donates == null)
      return false;
     for(let i = 0; i < this.profileService.donates.length; i++){
       flag = flag && this.profileService.donates[i].isSelected;
     }
     return flag;
   }

   toggleCheckboxes(){
     if(this.profileService.donates == null)
      return;
     if(this.donatesSelected){
      for(let i = 0; i < this.profileService.donates.length; i++){
       this.profileService.donates[i].isSelected = false;
      }
     }
     else{
        for(let i = 0; i < this.profileService.donates.length; i++){
          this.profileService.donates[i].isSelected = true;
        }
     }
   }
}
