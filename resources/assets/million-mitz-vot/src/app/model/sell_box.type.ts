export class SellBox {
  public id         :number;
  public title      :string;
  public price      :number;
  public detail     :string;
  public type       :string;
  public amount     :number;
  public sell_count :number;
  public main_image :string;
  public images     :string[];
  public created_at :string;
  public updated_at :string;

  constructor(){
    this.id           = 0;
    this.title        = "";
    this.price        = 0;
    this.detail       = "";
    this.type         = "";
    this.amount       = 0;
    this.sell_count   = 0;
    this.main_image   = "";
    this.images       = [];
    this.created_at   = "";
    this.updated_at   = "";
  }
}
