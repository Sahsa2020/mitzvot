import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { AuthenticateService, USER_TYPE } from '../../authenticate.service';
import { GeneralService } from '../../general.service';

@Component({
  selector: 'app-sponsor',
  templateUrl: './sponsor.component.html',
  styleUrls: ['./sponsor.component.css']
})
export class SponsorComponent implements OnInit {

  public model:any = {};
  public is_sponsor:boolean = false;
  constructor(public authService:AuthenticateService, public general: GeneralService,  public lang: LanguageService, public router: Router, public appState: StateService) {
    this.appState.set("one_page_menu_selected", 11);
    // this.refreshTimer();
    this.findSponsor();
  }

  ngOnInit() {
  }

  addSponsor(profileForm) {
    console.log(this.model);
    if (!this.is_sponsor) {
      this.general.addSponsor(this.model).subscribe(
        result => {
          if (result) {
            this.is_sponsor = true;
          }
       });
    } else {
      this.general.updateSponsor(this.model).subscribe(
        result => {
          if (result) {
            this.is_sponsor = true;
          }
       });
    }
  }

  findSponsor() {
    if (!this.is_sponsor) {
      this.general.findSponsor().subscribe(
        result => {
          if (result.success) {
            console.log(result.data[0]);
            this.is_sponsor = true;
            this.model = result.data[0];
          }
       });
    }
  }

}
