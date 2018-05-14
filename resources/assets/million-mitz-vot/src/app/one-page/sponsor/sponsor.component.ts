import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { AuthenticateService, USER_TYPE } from '../../authenticate.service';
import { GeneralService } from '../../general.service';

@Component({
  selector: 'app-become-sponsor-page',
  templateUrl: './sponsor.component.html',
  styleUrls: ['./sponsor.component.css']
})
export class SponsorComponent implements OnInit {
  public model:any = {country_id: 0, box_count: 50};
  public is_sponsor:boolean = false;
  public states: any[] = [];
  public countries: any[] = [];
  public places: any[] = [];
  constructor(public authService:AuthenticateService, public general: GeneralService,  public lang: LanguageService, public router: Router, public appState: StateService) {
    this.appState.set("one_page_menu_selected", 11);
    // this.refreshTimer();
    // this.findSponsor();
    this.getCountries();
  }

  ngOnInit() {
  }

  getCountries() {
    this.general.getCountries().subscribe(
      countries => {
        this.countries = countries;
        if (this.countries.length > 0){
          this.model.country_id = this.countries[0].id;
        }
        this.findSponsor();
      }
    );
  }

  getStates(){
    this.general.getStates(this.model.country_id).subscribe(
      states => {
        this.states = states;
        if(this.states.length > 0 && this.model.state_id == null){
          this.model.state_id = this.states[0].id;
        }
      }
    );
  }

  getPlaces(){
    this.general.getPlaces(this.model.country_id, this.model.state_id).subscribe(
      places => {
        this.places = places;
      }
    )
  }

  addSponsor(profileForm) {
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
    this.general.findSponsor().subscribe(
      sponsor => {
        if(sponsor.id != null){
          this.model = sponsor;
        }
        console.log("SDFSDFSDF");
        this.getStates();
      });
  }

}
