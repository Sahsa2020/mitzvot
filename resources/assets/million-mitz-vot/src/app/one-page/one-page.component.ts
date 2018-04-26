import { Component, OnInit, HostListener } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../language.service';
import { StateService } from '../state.service';
import { GeneralService } from '../general.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../authenticate.service';
import { environment } from '../../environments';

declare var jQuery:any;
declare var document:any;

@Component({
  selector: 'app-one-page',
  templateUrl: 'one-page.component.html',
  styleUrls: ['one-page.component.css']
})

export class OnePageComponent implements OnInit {
  @HostListener('window:scroll', ['$event'])
  scrollMoved(event) {
    // console.debug("Scroll Event", document.body.scrollTop);
    this.scrollPos = document.body.scrollTop;
  }
  public is_logged: boolean = false;
  public scrollPos: number = 0;
  public USER_SIGNED_INFO: any = USER_SIGNED_INFO;
  public USER_TYPE: any = USER_TYPE;
  public SERVER_URL: string = environment.serverUrl;
  public is_mobile_menu_clicked = false;
  constructor(public lang: LanguageService, public general: GeneralService, public router: Router, public appState: StateService, public authenticate: AuthenticateService) {
    this.authenticate.validateToken();
  }
  log_out(){
    // this.router.navigate(['/login']);
  }
  ngOnInit() {
    // jQuery('body').addClass(' white-background ');
  }
  ngOnDestroy() {
    // jQuery('body').removeClass('white-background');
  }
  tr(tran: string): string
  {
    return this.lang.tr("onepage." + tran);
  }
  showMobileMenu(clicked, event) {
    this.is_mobile_menu_clicked = clicked;
  }
}
