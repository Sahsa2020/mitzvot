import { Component, OnInit, trigger, state, style, transition, animate, keyframes, AfterViewChecked} from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { OnePageService } from '../one-page.service';
import { ProfileService } from '../profile/profile.service';
import { AuthenticateService } from '../../authenticate.service';
import { environment } from '../../../environments';

import { SellBox } from '../../model/sell_box.type';
import { Donate } from '../../model/donate.type';

declare var jQuery:any;

@Component({
  selector: 'app-sell-board',
  templateUrl: './sell-board.component.html',
  styleUrls: ['./sell-board.component.css']
})
export class SellBoardComponent implements OnInit {
  public sellBoxes:SellBox[] = [];
  public curSellBox:SellBox;
  public buy_count:number = 1;
  public donate_id:number=0;
  public token:string;
  public paySuccess:number;
  public selDonate:Donate;
  public serverUrl: string = environment.serverUrl;
  public discountPercent: number = 0;
  public isLoadingDiscountPercent: boolean = false;
  public is_selected_checkout: boolean = false;
  public input_errorMessage = "close";
  public successMessage = "";

  public payer_name = "";
  public payer_city = "";
  public payer_email = "";
  public payer_country = "";
  public payer_state = "";
  public payer_address = "";
  public payer_comment = "";
  public discount_code = "";

  // public card_number:string="";
  // public cvv_code:string="";
  // public exp_year:string="2016";
  // public exp_month:number=3;
  // public title:string = "Cool CBox";
  // public amount:number = 1;
  // public price:number = 47.01;
  // public type:number = 1;
  // public detail:string = "Lorem ipsum dolor ut sit ame dolore adipiscing elit, sed nonumy nibh sed euismod laoreet dolore magna aliquarm erat volutpat Nostrud duis molestie at dolore.";

  constructor(public lang: LanguageService, public router: Router, private authenticateService: AuthenticateService, public appState: StateService, public generalService: GeneralService, public onePageService:OnePageService, public route:ActivatedRoute, public profileService:ProfileService) {
    this.appState.set("one_page_menu_selected", 6);
    this.curSellBox = new SellBox();
    this.selDonate = new Donate();

    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.token = authenticateService.token;
    this.onePageService.getSellBoxes().subscribe(
     result => {
       if (result.success) {
           this.sellBoxes = result.data;
           this.curSellBox = this.sellBoxes[0];
           this.appState.errorMessage = "";
       } else {
         this.appState.errorMessage = this.tr("GET_FAILED");
       }
       this.appState.closeLoading();
     });

     this.appState.setLoading('Loading ...');
     this.profileService.getAllDonates(0,5,'', 'name',0).subscribe(
      result => {
        if(result != true)
          this.appState.errorMessage = 'Donates Load Error';
        this.appState.closeLoading();
      });

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

  changeAmount(num:number){
    this.buy_count += num;
    if (this.buy_count == 0) {
      this.buy_count = 1;
    }
  }

  get totalPrice(){
    if(this.discountPercent < 0 || this.discountPercent >= 100)
      return 0;
    let price: number = Math.round(this.buy_count * this.curSellBox.price * (100 - this.discountPercent));
    return  price / 100;
  }

  selectBox(id:number){
    this.curSellBox = this.sellBoxes[id];
    for (let sb of this.sellBoxes) {
        if (sb.id == id) {
            this.curSellBox = sb;
            break;
        }
    }
  }

  donateOverview(){
    this.selDonate = new Donate();
    for (let i = 0; i < this.profileService.donates.length; i++) {
        if (this.profileService.donates[i].id == this.donate_id) {
          this.selDonate = this.profileService.donates[i];
        }
    }
  }

  refreshDiscount(){
    let code = jQuery('#sell_board_discount_code').val();
    if(code == ""){
      this.discountPercent = 0;
      return;
    }
    this.isLoadingDiscountPercent = true;
    this.onePageService.getDiscountPercent(code).subscribe(
      res => {
       let result: any = res;
       if (result.success) {
           this.discountPercent = result.percent;
       }
       this.isLoadingDiscountPercent = false;
      },
      error => {this.isLoadingDiscountPercent = false;}
    );
  }

  inputValidated() {
    var is_input_validated = true;
    if (this.payer_name == "")
      is_input_validated = false;
    if (this.payer_city == "")
      is_input_validated = false;
    if (this.payer_email == "")
      is_input_validated = false;
    if (this.payer_country == "")
      is_input_validated = false;
    if (this.payer_state == "")
      is_input_validated = false;
    if (this.payer_address == "")
      is_input_validated = false;
    if (this.payer_comment == "")
      is_input_validated = false;
    if (this.discount_code == "")
      is_input_validated = false;

    if (!is_input_validated) {
      this.input_errorMessage = "";
    }      
  }
}
