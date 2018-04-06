export class Score {
  public id: number;
  public name:string;
  public image_url:string;
  public type: number;
  public address: string;
  public score: number;
  public box_count: number;
  public age: number;
  public school: string;
  public company: string;
  public city: string;
  public country: string;
  public birthday: string;
  public daily_count: number;
  public is_current_user_following: boolean;
  public followingUsers: any[];
  constructor(){
    this.id = 0;
    this.name = "";
    this.image_url = "";
    this.type = 0;
    this.address = "";
    this.score = 0;
    this.box_count = 0;
    this.age = 0;
    this.school = "";
    this.company = "";
    this.city = "";
    this.country = "";
    this.birthday = "";
    this.daily_count = 0;
    this.is_current_user_following = false;
    this.followingUsers = [];
  }
}
