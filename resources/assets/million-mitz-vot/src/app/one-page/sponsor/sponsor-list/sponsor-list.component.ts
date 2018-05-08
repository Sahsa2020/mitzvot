import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { AuthenticateService, USER_TYPE } from '../../../authenticate.service';
import { GeneralService } from '../../../general.service';

@Component({
  selector: 'app-sponsor-list',
  templateUrl: './sponsor-list.component.html',
  styleUrls: ['./sponsor-list.component.css']
})
export class SponsorListComponent implements OnInit {

  public model:any = {};
  public model_:any = {};
  public is_sponsor:boolean = false;
  constructor(public authService:AuthenticateService, public general: GeneralService,  public lang: LanguageService, public router: Router, public appState: StateService) {
    this.appState.set("one_page_menu_selected", 11);
    // this.refreshTimer();
    // this.findSponsor();    
  }

  ngOnInit() {
  }

  onSearch() {
    this.general.getSponsors(this.model).subscribe(
      result => {
        if (result.success) {
          this.is_sponsor = true;
          this.model_ = result.data[0];
          console.log(this.model_);          
        }
     });
  }

}
