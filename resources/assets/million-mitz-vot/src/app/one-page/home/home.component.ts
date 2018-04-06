import { Component, OnInit, trigger, state, style, transition, animate, keyframes, AfterViewChecked} from '@angular/core';
import { TimerObservable } from "rxjs/observable/TimerObservable";
import { Router, ActivatedRoute, Params } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { OnePageService } from '../one-page.service';
import { ProfileService } from '../profile/profile.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../authenticate.service';
import { environment } from '../../../environments';

import { Observable, Subscription } from 'rxjs/Rx';
declare var jQuery:any;
declare var videojs: any;
@Component({
  selector: 'app-home',
  templateUrl: 'home.component.html',
  styleUrls: ['home.component.css']
})
export class HomeComponent implements OnInit {
  public daily_leaders: any[];
  public errorMessage: string = "";
  public memberBoxAmount: number = 0;
  public userBoxAmount: number = 0;
  public category: number = 0;
  public slider_flag: boolean = false;
  public timer: any;
  public timerSubscription: any;
  public USER_SIGNED_INFO: any = USER_SIGNED_INFO;
  public USER_TYPE: any = USER_TYPE;
  public isDestroyed: boolean = false;
  public paySuccess:number;
  public serverDate: any = Date.now();
  public curTime: any = 3600 * 24;
  public curDate: string = "";
  public SERVER_URL: string = environment.serverUrl;
  public ageGroup: any = {
    4: "3~5",
    6: "5~8",
    9: "8~11",
    12: "11~13",
    15: "13~18",
    20: "18~"
  };
  constructor(public lang: LanguageService, public router: Router, public appState: StateService, public generalService: GeneralService, public authenticate:AuthenticateService, public onePageService:OnePageService, public route:ActivatedRoute, public profile: ProfileService) {
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.appState.set("one_page_menu_selected", 1);
    this.generalService.isGeneralInfoFirstLoad = true;
    this.profile.getServerTime().subscribe(
      (res: any) => {
        let data = res.data;
        this.curTime = 3600 * 24 - data.time;
        this.curDate = data.date;
      },
      error =>{
        // this.errorMessage = "There's some trouble with network.";
      }
    );
    this.generalService.getGeneralInfo().subscribe(result => {
      //  if(result)
      //    this.errorMessage = "";
      //  else
      //    this.errorMessage = this.tr("GET_GENERALINFO_FAILED");//"Please check your email and password again.";
       this.appState.closeLoading();
       this.slider_flag = true;
     });
    let me = this;
    let timer = TimerObservable.create(1000, 1000);
    this.timerSubscription = timer.subscribe(t => {
      if(this.curTime <= 0){
        this.timerSubscription.unsubscribe();
        setTimeout(3000, function(){
          location.reload();
        });
        return;
      }
      this.curTime --;
    });
  }

  toggleCategory(){
    if(this.category === 0)
      this.category = 1;
    else
      this.category = 0;
  }
  //Init RevolutionSlider
  initRevoSlider(){
    jQuery('.fullwidthabnner').show();
    jQuery('.fullwidthabnner').revolution({
        sliderType: "hero",
        startheight:517,
        startwidth: 950,

        hideCaptionAtLimit:0,         // It Defines if a caption should be shown under a Screen Resolution ( Basod on The Width of Browser)
        hideAllCaptionAtLilmit:0,       // Hide all The Captions if Width of Browser is less then this value
        hideSliderAtLimit:0,          // Hide the whole slider, and stop also functions if Width of Browser is less than this value

        shadow:1,                               //0 = no Shadow, 1,2,3 = 3 Different Art of Shadows  (No Shadow in Fullwidth Version !)
        fullWidth:"off",                          // Turns On or Off the Fullwidth Image Centering in FullWidth Modus
        delay: 1000,
        hideThumbs: true,
        fullScreen: "off",
        touchenabled:"on",                      // Enable Swipe Function : on/off
        onHoverStop:"off",                       // Stop Banner Timet at Hover on Slide on/off
        fullScreenOffsetContainer: ""
    });
  }

  refreshPage(){
    let me = this;
    this.generalService.getGeneralInfo().subscribe(
     result => {
      //  if(!this.isDestroyed)
        // setTimeout(function(){me.refreshPage();}, 5000);
     });
  }

  ngOnInit() {
    this.isDestroyed = false;

    this.route.params.forEach((params: Params) => {
      this.paySuccess = params && params['member_pay_success'];
      let status = params && params['status'];
      console.log(status);
      if(status == 'permission_denied'){
        this.appState.errorMessage = 'You are not authorized to access this resource.';
      }
    });
    // this.initRevoSlider();
  }

  ngOnDestroy() {
    this.isDestroyed = true;
    this.timerSubscription.unsubscribe();
    // jQuery('.fullwidthabnner').revolution('destroy');
  }

  tr(tran: string): string
  {
    return this.lang.tr("home." + tran);
  }

  get serverHour(){
    return Math.floor(this.curTime / 3600);
  }

  get serverMinute(){
    return Math.floor(this.curTime / 60) % 60;
  }

  get serverSecond(){
    return this.curTime % 60;
  }
}
