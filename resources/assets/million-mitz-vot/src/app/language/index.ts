import {en} from './en';
import {de} from './de';
export class Language{
  public _current: any;
  constructor(public lang: string)
  {
    this.setLanguage(lang);
    this._current = en;
  }
  public setLanguage(lang: string)
  {
    let me = this;
    switch(lang)
    {
      case 'de':
        me._current = de;
        break;
      default:
        me._current = en;
    }
  }
  public tr(tran: string): string
  {
    let result:string = '';
    eval("result=this._current." + tran);
    return result;
  }
}
