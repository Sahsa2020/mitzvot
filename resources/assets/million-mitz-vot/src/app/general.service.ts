import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { environment } from '../environments';
import { Score } from './model/score.type';
import { Leader } from './model/leader.type';
import { Router } from '@angular/router';
import { AuthenticateService } from './authenticate.service';
import 'rxjs/add/operator/map'

export const MENU_TITLE = {
  HOSPITAL: "Hospital",
  SCHOOL: "School",
  FOOD_BANK: "Food Bank",
  TORAH_LEARNING: "Torah Learning",
  FAMILY_SERVICES: "Family Services",
};

@Injectable()
export class GeneralService {
  public serverUrl: string = environment.serverUrl;
  public scores: Score[];
  public totalCount: number = 0;
  public dailyLeaders: any[];
  public lifeLeaders: any[];
  public commonUsers: any[];
  public topInstitution: Leader[];
  public topSchool: Leader[];
  public cbox_total: number = 0;
  public life_total: number = 0;
  public daily_total: number = 0;
  public organizations: any[] = [];
  public isGeneralInfoFirstLoad:boolean = true;
  public unread_messages: number = 0;
  public followingUser: any[] = [];
  public firmware_version: any = {major_version: 0, minor_version: 0};
  public allPersons: any[];
  constructor(private http: HttpClient, public authService: AuthenticateService) {
    this.dailyLeaders = [];
    this.lifeLeaders = [];
    this.topInstitution = [];
    this.topSchool = [];
    this.getUnreadMessageCount();
    this.getFirmwareVersion();
    this.allPersons= [];
  }

  getFirmwareVersion(){
    this.authService.get('/api/v1/getFirmwareVersion')
        .subscribe((res: any) => {
            if (res.status == 'ok') {
              this.firmware_version = {major_version: res.major_version, minor_version: res.minor_version};
            }
        }, error => {});
  }

  getScores(start:number, length: number, sort_item: string, sort_rule: boolean, search: string, filter: string):Observable<boolean> {
    if(filter == 'organization')
      filter = 'school';
    let sort_rule_string: string = "asc";
    if(sort_rule)
      sort_rule_string = "desc";
    return this.authService.get('/api/v1/getScore?start=' + start + '&length=' + length + '&sort_item=' + sort_item + '&sort_rule=' + sort_rule_string + '&search=' + search + '&filter=' + filter)
        .map((res: any) => {
            if (res.success == true) {
                for(let i = 0; i < res.data.data.length; i++)
                  if(res.data.data[i].image_url == "")
                    res.data.data[i].image_url = "assets/global/img/default_avatar.jpg";
                  else
                  {
                    let image_v = Math.random();
                    res.data.data[i].image_url += "?v=" + image_v;
                  }
                this.scores = res.data.data;
                this.followingUser = res.data.following;
                this.totalCount = res.data.total;
                return true;
            } else {
              return false;
            }
        });
  }

  getUnreadMessageCount(){
    let me = this;
    this.authService.get('/api/v1/profile/unreadMessages?cur_conversation_id=0').subscribe((result: any) =>{
      if(result.success)
        me.unread_messages = result.data;
      setTimeout(function(){
        me.getUnreadMessageCount();
      }, 5000);
    }, error =>{
      setTimeout(function(){
        me.getUnreadMessageCount();
      }, 5000);
    });
  }

  getGeneralInfo():Observable<boolean> {
    return this.authService.get('/api/v1/getGeneralInfo')
        .map((res: any) => {
            if (res.success == true) {
                //Check if avatar exists
                for(let i = 0; i < res.dailyLeader.length; i++)
                  for(let j = 0; j < res.dailyLeader[i].length; j++)
                    if(res.dailyLeader[i][j].image_url == "")
                      res.dailyLeader[i][j].image_url = "assets/global/img/default_avatar.jpg";
                    else{
                      let image_v = Math.random();
                      res.dailyLeader[i][j].image_url += "?v=" + image_v;
                    }
                for(let i = 0; i < res.lifeLeader.length; i++)
                  for(let j = 0; j < res.lifeLeader[i].length; j++)
                    if(res.lifeLeader[i][j].image_url == "")
                      res.lifeLeader[i][j].image_url = "assets/global/img/default_avatar.jpg";
                    else{
                      let image_v = Math.random();
                      res.lifeLeader[i][j].image_url += "?v=" + image_v;
                    }
                for(let i = 0; i < res.organizations.length; i++)
                  for(let j = 0; j < res.organizations[i].length; j++)
                    if(res.organizations[i][j].image_url == "")
                      res.organizations[i][j].image_url = "assets/global/img/default_avatar.jpg";
                    else{
                      let image_v = Math.random();
                      res.organizations[i][j].image_url += "?v=" + image_v;
                    }
                this.dailyLeaders = res.dailyLeader;
                this.lifeLeaders = res.lifeLeader;
                this.organizations = res.organizations;
                this.allPersons = this.lifeLeaders.concat(this.organizations);
                                
                if(this.isGeneralInfoFirstLoad)
                  this.commonUsers = res.users;
                this.isGeneralInfoFirstLoad = false;
                this.cbox_total = res.cbox_total;
                this.daily_total = res.daily_total;
                this.life_total = res.life_total;
                return true;
            } else {
              return false;
            }
        });
  }

  getAdminUsers():Observable<any> {
    return this.authService.get('/api/v1/getAdminUsers')
        .map((res: any) => {
            if (res.success == true) {
                return res.admins;
            } else {
              return [];
            }
        });
  }

  followUser(follow_user_id: number, is_follow: boolean){
    if(is_follow)
      return this.authService.postFormData('/api/v1/profile/follow', "follow_user_id=" + follow_user_id)
          .map((res: any) => {
              if (res.success == true)
                return true;
              else
                return false;
          });
    else
      return this.authService.postFormData('/api/v1/profile/unFollow', "follow_user_id=" + follow_user_id)
          .map((res: any) => {
              if (res.success == true)
                return true;
              else
                return false;
          });
  }

  getFriends(search:String) {
    return this.authService.get('/api/v1/profile/friends?search=' + search)
    .map((res: any) => {
        if (res.success == true) {
            return res.data;
        } else {
          return [];
        }
    });
  }
  
  createNewPost(content:String) {
    return this.authService.post('/api/v1/posts', {content: content})
    .map((res: any) => {
        if (res.status == true) {
            return res.data;
        } else {
          return null;
        }
    });
  }

  createNewComment(comment: String, postId: any) {
    return this.authService.post('/api/v1/posts/' + postId + "/comments", {comment: comment})
    .map((res: any) => {
        if (res.status == true) {
            return res.comment;
        } else {
          return null;
        }
    });
  }

  likePost(postId: any) {
    return this.authService.post('/api/v1/posts/' + postId + "/likes", {})
    .map((res: any) => {
        if (res.status == true) {
            return res;
        } else {
          return null;
        }
    });
  }

  getAllPosts(subUrl) {
    return this.authService.get('/api/v1/posts' + subUrl)
    .map((res: any) => {
        if (res.status == true) {
            return res.posts;
        } else {
          return [];
        }
    });
  }

  updatePersonalDetails(params) {
    return this.authService.postFormData('/api/v1/profile/updatePersonalDetails?', "name=" + params.name + 
    "&email=" + params.email + "&phone=" + params.phone + "&address=" + params.address + "&city=" + params.city + "&birthday=" + params.birthday + "&country=" + params.country )
      .map((result: any) =>{
        if(result.success)
          // console.log(result.data);
          return result;
      }, error =>{
          // console.log(error);
          return [];
    });
  }

  updateBankDetails(params) {
    return this.authService.postFormData('/api/v1/profile/updateBankDetails?', "bank_account=" + params.bank_account + 
    "&routing_number=" + params.routing_number + "&account_number=" + params.account_number + "&name_of_bank_account=" + params.name_of_bank_account + "&bank_name=" + params.bank_name + "&account_type=" + params.account_type)
      .map((result: any) =>{
        if(result.success)
          return result;
        else
          return result;
      }, error =>{
          // console.log(error);
          return [];
    });
  }

  updateGoals(params) {
    return this.authService.postFormData('/api/v1/profile/updateGoals?', "goal_daily=" + params.goal_daily + 
    "&goal_weekly=" + params.goal_weekly + "&goal_monthly=" + params.goal_monthly)
      .map((result: any) =>{
        if(result.success)
          return result;
        else
          return result;
      }, error =>{
          // console.log(error);
          return [];
    });
  }

  updatePassword(params) {
    return this.authService.postFormData('/api/v1/profile/updatePassword?', "cur_password=" + params.cur_password + 
    "&new_password=" + params.new_password + "&confirm_password=" + params.confirm_password)
      .map((result: any) =>{
        if(result.success)
          return result;
        else
          return result;
      }, error =>{
          // console.log(error);
          return [];
    });
  }

  updateVideo(params) {
    return this.authService.postFormData('/api/v1/profile/updateVideo?', "weekly_mail_video=" + params.weekly_mail_video)
      .map((result: any) =>{
        if(result.success)
          // console.log(result.data);
          return result.data;
      }, error =>{
          // console.log(error);
          return [];
    });
  }

  deleteAccount() {
    return this.authService.postFormData('/api/v1/profile/deleteAccount?', "del_flg=" + true)
      .map((result: any) =>{
        if(result.success)
          // console.log(result.data);
          return result.data;
      }, error =>{
          // console.log(error);
          return [];
    });
  }

  addSponsor(params) {
    return this.authService.postFormData('/api/v1/sponsors/add?', "country=" + params.country + "&box_count=" + params.box_count + "&state=" + params.state + "&city=" + params.city)
      .map((result: any) =>{
        if(result.success)
          // console.log(result.data);
          return result;
      }, error =>{
          // console.log(error);
          return [];
    });
  }

  updateSponsor(params) {
    return this.authService.postFormData('/api/v1/sponsors/update?', "country=" + params.country + "&box_count=" + params.box_count + "&state=" + params.state + "&city=" + params.city)
      .map((result: any) =>{
        if(result.success)
          // console.log(result.data);
          return result;
      }, error =>{
          // console.log(error);
          return [];
    });
  }

  findSponsor() {
    return this.authService.get('/api/v1/sponsors/find')
    .map((res: any) => {
        if (res.success) {
            return res;
        } else {
          return [];
        }
    });
  }

  getSponsors(params) {
    return this.authService.get('/api/v1/sponsors/?search=' + params.country)
      .map((res: any) => {
        if (res.success) {
            return res;
        } else {
          return [];
        }
    });
  }
}
