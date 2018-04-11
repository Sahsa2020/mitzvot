import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { AuthenticateService, USER_TYPE } from '../../authenticate.service';

@Component({
  selector: 'app-sponsor',
  templateUrl: './sponsor.component.html',
  styleUrls: ['./sponsor.component.css']
})
export class SponsorComponent implements OnInit {

  constructor(public authService:AuthenticateService, public lang: LanguageService, public router: Router, public appState: StateService) {
    this.appState.set("one_page_menu_selected", 11);
    // this.refreshTimer();
  }

  ngOnInit() {
  }

}
