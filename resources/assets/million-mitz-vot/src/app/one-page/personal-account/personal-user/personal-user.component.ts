import { Component, OnInit, ViewEncapsulation, ElementRef, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../../authenticate.service';
import { GeneralService } from '../../../general.service';
import { Score } from '../../../model/score.type';
import { ProfileService } from '../../';
declare var jQuery:any;

@Component({
  selector: 'app-personal-user',
  templateUrl: './personal-user.component.html',
  styleUrls: ['./personal-user.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class PersonalUserComponent implements OnInit {

  public curPage:number = 1;
  public totalCount:number = 0;
  public itemsPerPage:number = 10;
  public current_item: number = 0;
  public searchString: string = "";
  // public typeStrings: string[] = [this.tr("INDIVIDUAL"), this.tr("INSTITUTION"), this.tr("SCHOOL")];
  public sortField: string = "daily_count";
  public sortDirection: boolean = true;
  public loadingCount: number = 0;
  public isDestroyed: boolean = false;
  public searchFilter: string = "school";
  public USER_SIGNED_INFO: any = USER_SIGNED_INFO;
  public USER_TYPE = USER_TYPE;
  public currentSelectedUser: Score = new Score();

  public errorMessage: string = "";
  public successMessage: string = "";
  public user_id: number = -1;
  // public sounds: any[] = [];

  constructor(public profileService: ProfileService, public generalService:GeneralService, public authService: AuthenticateService, public lang: LanguageService, public router: Router, public appState: StateService) {
    this.refreshTable({page: this.curPage});
  }

  refreshTable(event){
    this.loadingCount ++;
    this.profileService.getUsers((event.page - 1)*this.itemsPerPage, this.itemsPerPage, this.sortField, this.sortDirection, this.searchString, this.searchFilter).subscribe(
     result => {
       if(result)
       {
         this.totalCount = this.profileService.total_user_count;
        //  this.scores = this.generalService.scores;
         this.loadingCount --;
         this.current_item = 0;
        //  this.currentSelectedUser = this.scores[0];
         if(this.loadingCount == 0 && !this.isDestroyed){
           let me = this;
          //  setTimeout(function(){me.refreshTable({page: me.curPage});}, 5000);
         }
       }
       else
       {
         this.appState.errorMessage = this.tr("GET_FAILED");//"Please check your email and password again.";
       }
       this.appState.closeLoading();
     });
  }

  ngOnInit() {
  }

  search(){
    this.refreshTable({page: this.curPage});
  }

  addFriend(){
    if(this.user_id < 0)
      return;
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.addFriend(this.user_id).subscribe(
     result => {
      //  if(result != false)
      //  {
        
      //  }
      //  else
      //  {
      //    this.appState.errorMessage = "Uploading failed.";
      //  }
       this.appState.closeLoading();
     });
  }

  tr(tran: string): string
  {
    return this.lang.tr("scoreboard." + tran);
  }

}
