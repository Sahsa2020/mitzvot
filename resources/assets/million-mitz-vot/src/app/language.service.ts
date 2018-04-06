import { Injectable } from '@angular/core';
import { Language } from './language/index';
import { Router, ActivatedRoute } from '@angular/router';
@Injectable()
export class LanguageService {
  public _language: Language;
  public lang: string = "en";
  constructor(private route: ActivatedRoute, private router: Router) {
    this._language = new Language("en");
  }
  public setLanguage(lang: string)
  {
    this.lang = lang;
    this._language.setLanguage(lang);
  }
  public tr(tran: string): string
  {
    return this._language.tr(tran);
  }
}
