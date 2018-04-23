import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { environment } from '../../../environments';
import { RegisterUser } from '../../model/user_register.type';
import { Box } from '../../model/box.type';
import { Member } from '../../model/member.type';
import { Donate } from '../../model/donate.type';
import { AuthenticateService } from '../../authenticate.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { LanguageService } from '../../language.service';
import { Router } from '@angular/router';

export const DONATE_CATEGORY = {
  HOSPITAL: "Hospital",
  SCHOOL: "School",
  FOOD_BANK: "Food Bank",
  TORAH_LEARNING: "Torah Learning",
  FAMILY_SERVICES: "Family Services",
};

export const DONATE_CATEGORY_INDEX = {
  HOSPITAL: "1",
  SCHOOL: "2",
  FOOD_BANK: "3",
  TORAH_LEARNING: "4",
  FAMILY_SERVICES: "5",
};


@Injectable()
export class ProfileService {
  public serverUrl: string = environment.serverUrl;
  public profile: RegisterUser;
  public boxes: Box[];
  public members: Member[];
  public myDonate:Donate;
  public donates:Donate[];
  public donate_count:number = 0;
  initToken(){
  }
  constructor(private http: HttpClient, private router: Router, private authService: AuthenticateService, private general: GeneralService, private stateService: StateService, private languageService: LanguageService) {
    this.initToken();
  }

  getServerTime():Observable<any>{
    return this.authService.get('/api/v1/getTime');
  }
  updateProfile(account: RegisterUser):Observable<any> {
    this.initToken();
    let data = new FormData();
    for(var key in account){
      if(key != "image_url" && key != 'image' && key != 'image_origin')
        data.append(key, account[key]);
    }
    return this.http.post(this.serverUrl + '/api/v1/profile', data);
  }
  updateProfileImage(account: RegisterUser): Observable<any> {
    let data = new FormData();
    data.append('image', account['image']);
    data.append('image_origin', account['image_origin']);
    return this.http.post(this.serverUrl + '/api/v1/profile/updateProfileImage', data);
  }
  updatePassword(passwords: any):Observable<boolean> {
    this.initToken();
    return this.authService.post('/api/v1/profile/updatePassword', "cur_password=" + passwords.curPassword + "&new_password=" + passwords.newPassword)
        .map((res: any) => {
          if (res.success == true) {
            return true;
          } else
            return false;
        }, error=>{return false;});
  }

  getBoxes():Observable<boolean> {
    return this.authService.get('/api/v1/boxes')
        .map((res: any) => {
            if (res.success == true) {
                this.boxes = res.data;
                for(let i = 0; i < this.boxes.length; i++)
                  if(this.boxes[i].major_version != res.major_version || this.boxes[i].minor_version != res.minor_version)
                    this.boxes[i].update_flag = 0;
                  else
                    this.boxes[i].update_flag = 1;
                return true;
            } else
              return false;
        }, error=>{return false;});
  }
  addBox(box:Box):Observable<any> {
    let data = new FormData();
    for(var key in box)
      data.append(key, box[key]);
    return this.authService.post('/api/v1/boxes', box)
        .map((res: any) => {
          if (res.success == true) {
            this.boxes.push(box);
            return res;
          } else
            return res;
        }, error=>{return error;});
  }
  updateBox(box:Box):Observable<boolean> {
    this.initToken();
    let headers = new Headers();
    let data:any = {device_id: box.device_id, country_code: box.country_code};
    return this.authService.put('/api/v1/boxes', box)
        .map((res: any) => {
          if (res.success == true) {
            return true;
          } else
            return false;
        }, error=>{return false;});
  }
  removeBox(box:Box):Observable<boolean>{
    this.initToken();
    let data = new FormData();
    let box_dev_id: any = box.device_id;
    data.append('device_id', box_dev_id);
    return this.authService.delete('/api/v1/boxes?' + 'device_id=' + box.device_id)
        .map((res: any) => {
          if (res.success == true) {
            return true;
          } else
            return false;
        }, error=>{return false;});
  }

  updateFirmware(device_id): Observable<boolean>{
    let data:any = {device_id: device_id};
    let headers = new Headers();
    return this.authService.post('/api/v1/boxes/updateBoxFirmware', data)
        .map((res: any) => {
          if (res.success == true) {
            return true;
          } else
            return false;
        }, error=>{return false;});
  }

  updateSound(device_id): Observable<boolean>{
    let data:any = {device_id: device_id};
    let headers = new Headers();
    return this.authService.post('/api/v1/boxes/updateBoxSound', data)
        .map((res: any) => {
          if (res.success == true) {
            return true;
          } else
            return false;
        }, error=>{return false;});
  }

  resetBox(box:Box):Observable<boolean>{
    this.initToken();
    let data:any = {uid: box.device_id};
    let headers = new Headers();
    return this.authService.post('/api/v1/resetBox', data)
        .map((res: any) => {
          if (res.success == true) {
            return true;
          } else
            return false;
        }, error=>{return false;});
  }
  getMembers():Observable<boolean> {
    this.initToken();
    let headers = new Headers();
    headers.append('Content-Type', 'application/json');
    // headers.append('token', this.token);
    return this.authService.get('/api/v1/members')
        .map((res: any) => {
            if (res.success == true) {
                this.members = res.data;
                return true;
            } else
              return false;
        }, error=>{return false;});
  }
  addMember(member:Member):Observable<any> {
    this.initToken();
    let headers = new Headers();
    let data = new FormData();
    console.log("MB: ",member.boxes);
    for(var key in member)
      data.append(key, member[key]);
    return this.authService.post('/api/v1/members', member)
        .map((res: any) => {
          if (res.success == true) {
            member.id = res.member_id;
            member.daily_count = res.daily_count;
            member.goal_daily = res.goal_daily;
            member.goal_weekly = res.goal_weekly;
            member.goal_monthly = res.goal_monthly;
            member.lifetime_count = res.lifetime_count;
            this.members.push(member);
            return res;
          } else
            return res;
        }, error=>{return error;});
  }
  updateMember(member:Member):Observable<any> {
    this.initToken();
    let headers = new Headers();
    // let data:any = {id: member.id, name: member.name, email: member.email, boxes: member.boxes, goal_daily:member.goal_daily, goal_weekly:member.goal_weekly, goal_monthly:member.goal_monthly};
    return this.authService.put('/api/v1/members', member)
        .map((res: any) => {
          if (res.success == true) {
            return res;
          } else
            return res;
        }, error=>{return error;});
  }
  removeMember(member:Member):Observable<boolean>{
    this.initToken();
    return this.authService.delete('/api/v1/members' + '?id=' + member.id)
        .map((res: any) => {
          if (res.success == true) {
            return true;
          } else
            return false;
        }, error=>{return false;});
  }

  saveDonate(donate:Donate):Observable<boolean> {
    this.initToken();
    let headers = new Headers();
    let data = new FormData();
    for(var key in donate){
      if(key == "picture")
        continue;
      data.append(key, donate[key]);
    }
    return this.authService.postFormData('/api/v1/donate', data)
        .map((res: any) => {
          if (res.success == true) {
            return true;
          } else
            return false;
        }, error=>{return false;});
  }
  
  getSounds():Observable<any>{
    let headers = new Headers();
    headers.append('Content-Type', 'application/json');
    return this.authService.get('/api/v1/sound')
        .map((res: any) => {
            if (res.success == true) {
                return res.data;
            } else
              return false;
        }, error=>{return false;});
  }

  saveSoundFiles(soundFile):Observable<any> {
    this.initToken();
    let headers = new Headers();
    let data = new FormData();
    data.append('file', soundFile);
    return this.authService.post('/api/v1/sound', data).map((res: any) => {
          if (res.success == true) {
            return res.data;
          } else
            return false;
        }, error=>{return false;});
  }

  removeSoundFile(id):Observable<boolean> {
    let headers = new Headers();
    return this.authService.delete('/api/v1/sound' + '?sound_id=' + id)
        .map((res: any) => {
          if (res.success == true) {
            return true;
          } else
            return false;
        }, error=>{return false;});
  }
  approveDonate(donate_id):Observable<boolean> {
    this.initToken();
    let headers = new Headers();
    let data = {donate_id: donate_id};
    return this.authService.post('/api/v1/donate/approve', data)
        .map((res: any) => {
          if (res.success == true) {
            return true;
          } else
            return false;
        }, error=>{return false;});
  }

  getDonate():Observable<boolean> {
    this.initToken();
    let headers = new Headers();
    headers.append('Content-Type', 'application/json');
    return this.authService.get('/api/v1/donate')
        .map((res: any) => {
            if (res.success == true) {
              if (res.data != null) {
                this.myDonate = res.data;
              } else {
                this.myDonate = new Donate();
              }
                return true;
            } else
              return false;
        }, error=>{return false;});
  }
  getAllDonates(start:number, length: number, search: string, filter: string, category: number):Observable<boolean> {
    let headers = new Headers();
    headers.append('Content-Type', 'application/json');
    return this.authService.get('/api/v1/all_donates?start=' + start + '&length=' + length + '&category=' + category + '&search=' + search + '&filter=' + filter)
        .map((res: any) => {
            if (res.success == true) {
              if (res.data != null) {
                for(let i = 0; i < res.data.length; i ++){
                  res.data[i].isSelected = false;
                  res.data[i].quantity = 0;
                }
                this.donates = res.data;
                this.donate_count = res.count;
              } else {
                this.donates = [];
              }

              return true;
            } else {
              return false;
            }
        }, error=>{return false;});
  }
}
