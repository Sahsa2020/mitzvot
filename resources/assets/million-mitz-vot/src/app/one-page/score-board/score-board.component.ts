import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../authenticate.service';
import { GeneralService } from '../../general.service';
import { Score } from '../../model/score.type';

@Component({
  selector: 'app-score-board',
  templateUrl: 'score-board.component.html',
  styleUrls: ['score-board.component.css']
})
export class ScoreBoardComponent implements OnInit {
  public successMessage: string = "";
  public scores: Score[];
  public curPage:number = 1;
  public totalCount:number = 0;
  public itemsPerPage:number = 10;
  public current_item: number = 0;
  public searchString: string = "";
  public typeStrings: string[] = [this.tr("INDIVIDUAL"), this.tr("INSTITUTION"), this.tr("SCHOOL")];
  public sortField: string = "daily_count";
  public sortDirection: boolean = true;
  public loadingCount: number = 0;
  public isDestroyed: boolean = false;
  public searchFilter: string = "school";
  public USER_SIGNED_INFO: any = USER_SIGNED_INFO;
  public USER_TYPE = USER_TYPE;
  public currentSelectedUser: Score = new Score();
  public ageGroup: any = {
    4: "3~5",
    6: "5~8",
    9: "8~11",
    12: "11~13",
    15: "13~18",
    20: "18~"
  };
  constructor(public generalService:GeneralService, public authService: AuthenticateService, public lang: LanguageService, public router: Router, public appState: StateService) {
    this.scores = [];
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.refreshTable({page: this.curPage});
    this.appState.set("one_page_menu_selected", 2);
  }
  refreshTable(event){
    this.loadingCount ++;
    this.generalService.getScores((event.page - 1)*this.itemsPerPage, this.itemsPerPage, this.sortField, this.sortDirection, this.searchString, this.searchFilter).subscribe(
     result => {
       if(result)
       {
         this.totalCount = this.generalService.totalCount;
         this.scores = this.generalService.scores;
         this.loadingCount --;
         this.current_item = 0;
         this.currentSelectedUser = this.scores[0];
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

  sort(sortField){
    if(this.sortField == sortField){
      if(!this.sortDirection)
        this.sortDirection = true;
      else
      {
        this.sortField = "id";
        this.sortDirection = false;
      }
    }
    else{
      this.sortField = sortField;
      this.sortDirection = false;
    }
    this.refreshTable({page: this.curPage});
  }

  search(){
    this.refreshTable({page: this.curPage});
  }
  
  selectUser(index){
    this.current_item = index;
    this.currentSelectedUser = this.scores[index];
  }

  ngOnInit() {
    this.isDestroyed = false;
  }
  ngOnDestroy(){
    this.isDestroyed = true;
  }

  followUser(){
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.generalService.followUser(this.scores[this.current_item].id, !this.scores[this.current_item].is_current_user_following).subscribe(res => {
      if(res){
        this.scores[this.current_item].is_current_user_following = !this.scores[this.current_item].is_current_user_following;
      }
      this.appState.closeLoading();
      if(this.scores[this.current_item].is_current_user_following)
        this.generalService.followingUser.push(this.scores[this.current_item]);
      else{
        for(let i = 0; i < this.generalService.followingUser.length; i++){
          if(this.generalService.followingUser[i].id == this.scores[this.current_item].id){
            this.generalService.followingUser.splice(i, 1);
          }
        }
      }
    },
    error =>{
      this.appState.errorMessage = "Network Error.";
      this.appState.closeLoading();
    });
  }

  showMyProfile(){
    let score: Score = new Score();
    score.id = this.authService.currentUser.id;
    score.name = this.authService.currentUser.name;
    score.address = this.authService.currentUser.address;
    score.country = this.authService.currentUser.country;
    score.image_url = this.authService.currentUser.image_url;
    this.currentSelectedUser = score;
  }

  tr(tran: string): string
  {
    return this.lang.tr("scoreboard." + tran);
  }
}
