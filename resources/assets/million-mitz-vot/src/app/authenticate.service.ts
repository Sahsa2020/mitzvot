import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpRequest, HttpResponse, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { environment } from '../environments';
import { RegisterUser } from './model/user_register.type';
import { Router, ActivatedRoute } from '@angular/router';
import 'rxjs/add/operator/share';
import 'rxjs/add/operator/map';
export const USER_SIGNED_INFO = {
  SIGNED_IN: 1,
  VALIDATING: 2,
  NOT_SIGNED_IN: 0
};
export const USER_TYPE = {
  INDIVIDUAL: "INDIVIDUAL",
  INSTITUTION: "INSTITUTION",
  SCHOOL: "SCHOOL",
  MEMBER: "MEMBER",
  ADMIN: "ADMIN",
  SHIPPER: "DROP_SHIPPER"
};

@Injectable()
export class AuthenticateService {
    public token: string = "";
    public type:number = -1;
    public isAdmin: number = 0;
    public serverUrl: string = environment.serverUrl;
    public isValidating: boolean = false;
    public email:string = "";
    public currentUser: any = null;
    
    constructor(private http: HttpClient, private route: ActivatedRoute, private router: Router) {
        this.validateToken();
    }


    _constructUserPath(): string {
      // return '/assets/api/v1';
      return this.serverUrl;
    }

    canActivate() {
        if (this.isLoggedIn() != USER_SIGNED_INFO.NOT_SIGNED_IN)
            return true;
        else {

            // Redirect user to sign in if signInRedirect is set
            this.router.navigate(['/home']);

            return false;
        }
    }

    validateToken(){
      this.isValidating = true;
      this.get('/api/v1/profile').subscribe(
        (res: any) => {
            console.log(res);
            if(res.success){
                this.currentUser = res.data;
            }
            this.isValidating = false;
        },
        error => {
          this.isValidating = false;
          let curUrl = this.router.url;
          if(curUrl.slice(0, 8) == '/profile'){
             this.router.navigate(['/home']);
          }
        }
      );
    }

    reset(email: string):Observable<boolean> {
      return this.post('/api/v1/sendPasswordToken', JSON.stringify({email: email})).map((response: any) => {
            if (response.result > 0) {
                return true;
            } else {
                return false;
            }
        }, error => {return false;});
    }

    resetPassword(data):Observable<boolean> {
      return this.post('/api/v1/resetPassword', JSON.stringify(data)).map((response: any) => {
            if (response.result > 0) {
                return true;
            } else {
                return false;
            }
        }, error => {return false;});
    }

    isLoggedIn(): number{
      if(this.isValidating)
        return USER_SIGNED_INFO.VALIDATING;
      if(this.currentUser != null)
        return USER_SIGNED_INFO.SIGNED_IN;
      else
        return USER_SIGNED_INFO.NOT_SIGNED_IN;
    }

    get(path: string): Observable<any> {
        return this.http.get(this._constructUserPath() + path, this.defaultHttpOptions());
    }

    post(path: string, data: any): Observable<any> {
        return this.http.post(this._constructUserPath() + path, data, this.defaultHttpOptions());
    }

    postFormData(path: string, data: any): Observable<any> {
        let baseRequestOptions: HttpParams;
        let baseHeaders: { [key:string]: string; } = {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        let httpOptions = {
            headers: baseHeaders,
            withCredentials: true,
        };
        return this.http.post(this._constructUserPath() + path, data, httpOptions);
    }
    put(path: string, data: any): Observable<any> {
        return this.http.put(this._constructUserPath() + path, data, this.defaultHttpOptions());
    }

    delete(path: string): Observable<any> {
        return this.http.delete(this._constructUserPath() + path, this.defaultHttpOptions());
    }

    patch(path: string, data: any): Observable<any> {
        return this.http.patch(this._constructUserPath() + path, data, this.defaultHttpOptions());
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
