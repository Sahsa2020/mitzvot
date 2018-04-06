export class Box {
  public id:number;
  public device_id: number;
  public user_id: number;
  public created_at: string;
  public updated_at: string;
  public country_code: string;
  public d_count: number;
  public lifetime_count: number;
  public amount: number;
  public deposit_amount: number;
  public secretCode: string;
  public major_version: number;
  public minor_version: number;
  public update_flag: number;
  public sound_update_flag: number;
  constructor(){
    this.id = 0;
    this.device_id = 0;
    this.user_id = 0;
    this.created_at = "";
    this.updated_at = "";
    this.country_code = "";
    this.lifetime_count = 0;
    this.d_count = 0;
    this.amount = 0;
    this.deposit_amount = 0;
    this.secretCode = "";
    this.major_version = 1;
    this.minor_version = 0;
    this.update_flag = 0;
    this.sound_update_flag = 0;
  }
}
