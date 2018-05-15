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

  countryChanged() {
    this.getStates(false);
  }

  stateChanged(state_id) {
    this.model.state_id = state_id;
    this.getPlaces();
  }

  getStates(is_default:boolean){
    this.general.getStates(this.model.country_id).subscribe(
      states => {
        this.states = states;
        if(this.states.length > 0 && !is_default){
          this.model.state_id = this.states[0].id;
        }
        if(this.model.state_id != null){
          this.getPlaces();
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

  addSponsor(place_id) {
    this.general.updateSponsor(place_id, this.model.box_count).subscribe(
      result => {
        if (result) {
          this.is_sponsor = true;
          this.getPlaces();
        }
      });
  }

  findSponsor() {
    this.general.findSponsor().subscribe(
      sponsor => {
        console.log(sponsor);
        if(sponsor.id != null){
          this.model = sponsor;
          this.is_sponsor = true;
          this.getStates(true);
        }
        else {
          this.getStates(false);
        }
      });
  }

}
