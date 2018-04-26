import { Component, OnInit } from '@angular/core';

import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { GeneralService } from '../../../general.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../../authenticate.service';
import { environment } from '../../../../environments';

@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.css']
})
export class MainComponent implements OnInit {

  public friends = [];
  public SERVER_URL: string = environment.serverUrl;
  constructor(public lang: LanguageService, public general: GeneralService, public router: Router, public appState: StateService, public authenticate: AuthenticateService) {
    this.authenticate.validateToken();

    this.appState.setLoading('Loading....');
    this.general.getFriends('').subscribe(result => {
      this.friends = result;
      // console.log(this.friends);
      console.log(this.friends[0].image_url);
      this.appState.closeLoading();
    });
  }
  
  ngOnInit() {
  }
}
