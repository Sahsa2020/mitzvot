import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../authenticate.service';
import { environment } from '../../../environments';
import { ProfileService } from '../';

@Component({
  selector: 'app-personal-account',
  templateUrl: './personal-account.component.html',
  styleUrls: ['./personal-account.component.css']
})
export class PersonalAccountComponent implements OnInit {

  public SERVER_URL: string = environment.serverUrl;
  constructor(public lang: LanguageService, public general: GeneralService, public router: Router, public appState: StateService, public authenticate: AuthenticateService, public profileService: ProfileService) {
    this.authenticate.validateToken();
  }

  ngOnInit() {
  }

  tr(tran: string): string
  {
    return this.lang.tr("scoreboard." + tran);
  }

}
