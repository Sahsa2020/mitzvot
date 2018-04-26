import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { GeneralService } from '../../../general.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../../authenticate.service';
import { environment } from '../../../../environments';

@Component({
  selector: 'app-friend',
  templateUrl: './friend.component.html',
  styleUrls: ['./friend.component.css']
})
export class FriendComponent implements OnInit {
  public friends = [];
  public current_item: number = 0;
  public curPage:number = 1;
  public itemsPerPage:number = 16;
  public SERVER_URL: string = environment.serverUrl;
  public searchString: string = "";
  public totalCount:number = 0;
  
  constructor(public lang: LanguageService, public general: GeneralService, public router: Router, public appState: StateService, public authenticate: AuthenticateService) {
    // this.authenticate.validateToken();

    this.appState.setLoading('Loading....');
    this.general.getFriends(this.searchString).subscribe(result => {
      this.friends = result;
      console.log(this.friends);
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
        this.friends = result;
        console.log(this.friends);
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

}
