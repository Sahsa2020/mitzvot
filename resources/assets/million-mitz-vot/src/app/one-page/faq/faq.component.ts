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

  is_0: boolean = true;
  is_1: boolean = true;
  is_2: boolean = true;
  is_3: boolean = true;

  is_0_clicked: boolean = true;
  is_1_clicked: boolean = true;
  is_2_clicked: boolean = true;
  is_3_clicked: boolean = true;

  constructor(public authService:AuthenticateService, public lang: LanguageService, public router: Router, public appState: StateService) {
    this.appState.set("one_page_menu_selected", 13);
    // this.refreshTimer();
  }

  ngOnInit() {
  }

  onClickAsk(event) {
    console.log(event.srcElement.className);
    if (event.srcElement.className == '0') {
      this.setState(0);
    }
    if (event.srcElement.className == '1') {
      this.setState(1);
    }
    if (event.srcElement.className == '2') {
      this.setState(2);
    }
    if (event.srcElement.className == '3') {
      this.setState(3);
    }
  }

  setState(index) {
    if (index == 0) {
      this.is_0_clicked = !this.is_0_clicked;
    }
    if (index == 1) {
      this.is_1_clicked = !this.is_1_clicked;
    }
    if (index == 2) {
      this.is_2_clicked = !this.is_2_clicked;
    }
    if (index == 3) {
      this.is_3_clicked = !this.is_3_clicked;
    }
  }

}
