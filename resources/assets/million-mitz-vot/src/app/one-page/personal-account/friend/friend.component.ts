import { Component, OnInit, ViewEncapsulation, ElementRef, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { GeneralService } from '../../../general.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../../authenticate.service';
import { environment } from '../../../../environments';
import { ProfileService } from '../../';


@Component({
  selector: 'app-friend',
  templateUrl: './friend.component.html',
  styleUrls: ['./friend.component.css']
})
export class FriendComponent implements OnInit {
  @ViewChild('add_box_dialog') add_box_dialog: any;
  @ViewChild('edit_box_dialog') edit_box_dialog: any;
  public friends = [];
  public daily = [];
  public weekly = [];
  public monthly = [];
  public current_item: number = 0;
  public curPage:number = 1;
  public itemsPerPage:number = 16;
  public SERVER_URL: string = environment.serverUrl;
  public searchString: string = "";
  public totalCount:number = 0;
  public friend_id:number = 0;
  
  constructor(public lang: LanguageService, public profileService:ProfileService, public general: GeneralService, public router: Router, public appState: StateService, public authenticate: AuthenticateService) {
    // this.authenticate.validateToken();

    this.appState.setLoading('Loading....');
    this.general.getFriends(this.searchString).subscribe(result => {
      this.friends = result.data;
      this.daily = result.daily;
      this.weekly = result.weekly;
      this.monthly = result.monthly;
      this.appState.closeLoading();
    });

    // this.refreshTable({page: this.curPage});
  }

  search(){
    this.refreshTable({});
  }

  refreshTable($event){
    this.general.getFriends(this.searchString).subscribe(
     result => {
       if(result)
       {
        this.friends = result.data;
        this.daily = result.daily;
        this.weekly = result.weekly;
        this.monthly = result.monthly;
       }
       else
       {
         
       }
       this.appState.closeLoading();
     });
    // this.loadingCount ++;
    // this.generalService.getScores((event.page - 1)*this.itemsPerPage, this.itemsPerPage, this.sortField, this.sortDirection, this.searchString, this.searchFilter).subscribe(
    //  result => {
    //    if(result)
    //    {
    //      this.totalCount = this.generalService.totalCount;
    //      this.scores = this.generalService.scores;
    //      this.loadingCount --;
    //      this.current_item = 0;
    //      this.currentSelectedUser = this.scores[0];
    //      if(this.loadingCount == 0 && !this.isDestroyed){
    //        let me = this;
    //       //  setTimeout(function(){me.refreshTable({page: me.curPage});}, 5000);
    //      }
    //    }
    //    else
    //    {
    //      this.appState.errorMessage = this.tr("GET_FAILED");//"Please check your email and password again.";
    //    }
    //    this.appState.closeLoading();
    //  });
  }

  ngOnInit() {
  }

  deleteFriend(){
    if(this.friend_id < 0)
      return;
    console.log(this.friend_id);
    // this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.deleteFriend(this.friend_id).subscribe(
     result => {
       this.appState.closeLoading();
       if (result) {
          this.search();
       }
     });
  }

}
