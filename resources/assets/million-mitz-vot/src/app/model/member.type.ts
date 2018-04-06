export class Member {
  public id:number;
  public user_id: number;
  public name: string;
  public email: string;
  public created_at: string;
  public updated_at: string;
  public boxes: number[];
  public goal_daily:number;
  public goal_weekly:number;
  public goal_monthly:number;
  public daily_count: number;
  public weekly_count: number;
  public monthly_count: number;
  public lifetime_count: number;
  public password: string;
  public amount_in_box: number;
  public phone: string;
  public address: string;
  public country: string;
  public city: string;
  public secretCodes: string[];
  constructor(){
    this.id = 0;
    this.user_id = 0;
    this.name = "";
    this.email = "";
    this.created_at = "";
    this.updated_at = "";
    this.boxes = [0];
    this.goal_daily = 0;
    this.goal_weekly = 0;
    this.goal_monthly = 0;
    this.daily_count = 0;
    this.weekly_count = 0;
    this.monthly_count = 0;
    this.lifetime_count = 0;
    this.password = "";
    this.amount_in_box = 0;
    this.phone = "";
    this.country = "";
    this.city = "";
    this.address = "";
    this.phone = "";
    this.secretCodes = [""];
  }
}
