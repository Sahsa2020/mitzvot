export class RegisterUser {
  public name: string;
  public password: string;
  public rpassword: string;
  public email: string;
  public type: number;
  public age: number;
  public school: string;
  public company: string;
  public phone: string;
  public address: string;
  public admin: number;
  public city: string;
  public country: string;
  public birthday: string;
  public image_url: string;
  public image: any;
  public image_origin: any;
  public goal_daily: number;
  public goal_monthly: number;
  public goal_weekly: number;
  public daily_count:number;
  public weekly_count:number;
  public monthly_count:number;
  public role: string;
  public weekly_mail_video: string;
  public weekly_mail_ignore: number;

  constructor(){
    this.name = "";
    this.password = "";
    this.rpassword = "";
    this.email = "";
    this.type = 1;
    this.age = 4;
    this.school = "";
    this.phone = "";
    this.address = "";
    this.company = "";
    this.city = "";
    this.country = "";
    this.birthday = "";
    this.image_url = "";
    this.image = "";
    this.image_origin = "";
    this.goal_daily = 0;
    this.goal_monthly = 0;
    this.goal_weekly = 0;
    this.daily_count = 0;
    this.weekly_count = 0;
    this.monthly_count = 0;
    this.admin = 0;
    this.role = "";
    this.weekly_mail_ignore = 1;
    this.weekly_mail_video = "";
  }
}
