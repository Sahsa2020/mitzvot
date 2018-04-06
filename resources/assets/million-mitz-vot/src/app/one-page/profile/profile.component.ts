import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { ProfileService } from './profile.service';
import { AuthenticateService, USER_TYPE } from '../../authenticate.service';

import { TimerObservable } from "rxjs/observable/TimerObservable";
declare var jQuery:any;
@Component({
  selector: 'app-profile',
  templateUrl: 'profile.component.html',
  styleUrls: ['profile.component.css']
})
export class ProfileComponent implements OnInit {
  public USER_TYPE: any = USER_TYPE;
  public timer: any;
  public serverDate: any = Date.now();
  public curTime: any = 3600 * 24;
  constructor(public authService:AuthenticateService, public lang: LanguageService, public router: Router, public appState: StateService, public profile: ProfileService) {
    this.appState.set("one_page_menu_selected", 3);
    // this.refreshTimer();
  }

  refreshTimer(){
    // this.profile.getServerTime().subscribe(response => {
    //   let res:any = response;
    //   res = res._body;
    //   // res = res.slice(0, 10) + 'T' + res.slice(11, 8);
    //   console.log("Time: ", res);
    //   let timer = TimerObservable.create(2000, 1000);
    //   let _time = new Date(res);
    //   this.serverDate = _time;
    //   console.log(_time);
    //   console.log(_time.getHours());
    //   this.curTime = this.curTime - (_time.getHours() * 3600 + _time.getMinutes() * 60 + _time.getSeconds());
    //   this.timer = timer.subscribe(tim => {
    //     if(this.curTime == 0){
    //       this.curTime = 3600 * 24;
    //       let me = this;
    //       setTimeout(function(){me.refreshTimer();}, 1000);
    //     }
    //     else
    //       this.curTime --;
    //   });
    // }, error => null);
  }

  ngOnInit() {
  }

  ngOnDestroy(){
    // this.timer.unsubscribe();
  }

  tr(tran: string): string
  {
    return this.lang.tr("profile." + tran);
  }

  hour(){
    let hour = Math.floor(this.curTime / 3600);
    if(hour < 10)
      return '0' + hour;
    else
      return '' + hour;
  }

  minutes(){
    let mins = Math.floor((this.curTime / 60) % 60);
    if(mins < 10)
      return '0' + mins;
    else
      return '' + mins;
  }

  seconds(){
    let secs = Math.floor(this.curTime % 60);
    if(secs < 10)
      return '0' + secs;
    else
      return '' + secs;
  }
}
