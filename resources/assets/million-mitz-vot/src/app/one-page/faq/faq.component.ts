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

  public ask_state: number = 1;
  public answer_state: boolean = false;
  public ask_answer_list = [
    {'ask': 'What the difference between Mitz and Mitzvot', 'answer': "Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate."},
    {'ask': 'How can put money in C -Box?', 'answer': "Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate."},
    {'ask': 'What the difference between Mitz and Mitzvot', 'answer': "Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate."},
    {'ask': 'How can put money in C -Box?', 'answer': "Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate."},

  ];
  constructor(public authService:AuthenticateService, public lang: LanguageService, public router: Router, public appState: StateService) {
    this.appState.set("one_page_menu_selected", 13);
    // this.refreshTimer();
  }

  ngOnInit() {
  }

  onClickAsk(index) {
    this.ask_state = index;
    this.answer_state = !this.answer_state;

  }

}
