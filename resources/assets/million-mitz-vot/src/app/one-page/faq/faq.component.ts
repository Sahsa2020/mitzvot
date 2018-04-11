import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { OnePageService } from '../one-page.service';
import { AuthenticateService, USER_SIGNED_INFO } from '../../authenticate.service';

@Component({
  selector: 'app-faq',
  templateUrl: './faq.component.html',
  styleUrls: ['./faq.component.css']
})
export class FaqComponent implements OnInit {

  constructor(public authService:AuthenticateService, public lang: LanguageService, public router: Router, public appState: StateService) {
    this.appState.set("one_page_menu_selected", 13);
    // this.refreshTimer();
  }

  ngOnInit() {
  }

}
