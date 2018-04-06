import { Component, OnInit, trigger, state, style, transition, animate, keyframes, AfterViewChecked} from '@angular/core';
import { Router , ActivatedRoute } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { OnePageService } from '../one-page.service';

declare var jQuery:any;

@Component({
  selector: 'app-activate',
  templateUrl: './activate.component.html',
  styleUrls: ['./activate.component.css']
})
export class ActivateComponent implements OnInit {
  public message = "";
  public title1 = "";
  public title2 = "";
  constructor(public lang: LanguageService, public router: Router, public appState: StateService, public generalService: GeneralService, public onePageService:OnePageService, public route:ActivatedRoute) {

    this.title1 = "Welcome!";
    this.title2 = " Please Activate Your Account.";
    this.message = "Hello.  Thanks for choosing millionmitzvot! We just sent you email to provide you activate your account. Please activate your account by checking your email. We may need to communicate important service level issues with you from time to time, so it's important we have an up-to-date email address for you on file.";
    this.route.params.forEach((params) => {
      if(params && params['success'] != null){
        if(params['success']){
          this.title1 = "Welcome!";
          this.title2 = " Your account has been approved.";
          this.message = "We are redirecting to home page within 5 seconds";
          let me = this;
          setTimeout(function(){
            me.router.navigate(['/home']);
          }, 5000);
        }
      }
    });
  }

  ngOnInit() {
  }

  ngOnDestroy() {

  }
}
