import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { environment } from '../environments';
import { Score } from './model/score.type';
import { Leader } from './model/leader.type';
import { Router } from '@angular/router';
import { AuthenticateService } from './authenticate.service';
import 'rxjs/add/operator/map'
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
}
