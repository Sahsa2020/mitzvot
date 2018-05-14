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

  is_0_clicked: boolean = true;
  is_1_clicked: boolean = true;
  is_2_clicked: boolean = true;
  is_3_clicked: boolean = true;

  public status_0 = '+';
  public status_1 = '+';
  public status_2 = '+';
  public status_3 = '+';

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
      if (this.is_0_clicked) {
        this.status_0 = '+';
      } else {
        this.status_0 = '-';
      }
    }
    if (index == 1) {
      this.is_1_clicked = !this.is_1_clicked;
      if (this.is_1_clicked) {
        this.status_1 = '+';
      } else {
        this.status_1 = '-';
      }
    }
    if (index == 2) {
      this.is_2_clicked = !this.is_2_clicked;
      if (this.is_2_clicked) {
        this.status_2 = '+';
      } else {
        this.status_2 = '-';
      }
    }
    if (index == 3) {
      this.is_3_clicked = !this.is_3_clicked;
      if (this.is_3_clicked) {
        this.status_3 = '+';
      } else {
        this.status_3 = '-';
      }
    }
  }

}
