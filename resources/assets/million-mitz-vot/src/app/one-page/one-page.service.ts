import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { environment } from '../../environments';
import { AuthenticateService } from '../authenticate.service';
import { StateService } from '../state.service';
import { LanguageService } from '../language.service';
import { Router } from '@angular/router';
import 'rxjs/add/operator/map'
import { SellBox } from '../model/sell_box.type';
import { ContactMessage } from '../model/contact_message.type';

@Injectable()
export class OnePageService {
  public serverUrl: string = environment.serverUrl;
  public sellBoxes: SellBox[];

  constructor(private http: HttpClient, private router: Router, private authenticateService: AuthenticateService, private stateService: StateService, private languageService: LanguageService) {
  }

  getSellBoxes():Observable<any> {
    return this.http.get(this.serverUrl + '/api/v1/sell/getSellBoxes', this.defaultHttpOptions()).map((res: any) => {
        return res;
    });
  }

  sendContactMessage(msg: ContactMessage):Observable<boolean>{
    return this.http.post(this.serverUrl + '/api/v1/sendContactMessage', msg, this.defaultHttpOptions()).map((res: any) => {
        if (res.success == true) {
            // this.sellBoxes = res.data;
            // console.log(this.sellBoxes);
            return true;
        } else {
            // this.check_token(res);
            return false;
        }
    });
  }

  getMemberBoxAmount():Observable<any> {
    return this.http.get(this.serverUrl + '/api/v1/members/getBoxAmount', this.defaultHttpOptions());
  }

  getUserBoxAmount(): Observable<any>{
    return this.http.get(this.serverUrl + '/api/v1/profile/getUserAmount', this.defaultHttpOptions());
  }
  
  getTransactionHistory():Observable<any> {
    return this.http.get(this.serverUrl + '/api/v1/getTransactionHistory', this.defaultHttpOptions());
  }

  getPaid(amount:any, password:string):Observable<boolean>{
    let data = new FormData();
    data.append("amount", amount);
    data.append("password", password);
    return this.http.post(this.serverUrl + '/api/v1/getPaid', data, this.defaultHttpOptions())
        .map((res: any) => {
          return res.success
        }, error=>{return false;});
  }

  getDiscountPercent(code: string){
    return this.http.get(this.serverUrl + '/api/v1/getDiscountPercent?code=' + code, this.defaultHttpOptions());
  }

  defaultHttpOptions(): any {
    let baseRequestOptions: HttpParams;
    let baseHeaders: { [key:string]: string; } = {
        'Content-Type': 'application/json',//'application/x-www-form-urlencoded; charset=UTF-8',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };
    let httpOptions = {
        headers: baseHeaders,
        withCredentials: true,
    };
    return httpOptions;
  }
}
