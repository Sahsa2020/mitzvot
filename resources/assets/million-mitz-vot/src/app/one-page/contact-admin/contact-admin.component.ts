import { Component, OnInit, trigger, state, style, transition, animate, keyframes, AfterViewChecked, ViewChild} from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { OnePageService } from '../one-page.service';
import { environment } from '../../../environments';
import { ContactMessage } from '../../model/contact_message.type';

declare var jQuery:any;

@Component({
  selector: 'app-contact-admin',
  templateUrl: './contact-admin.component.html',
  styleUrls: ['./contact-admin.component.css']
})
export class ContactAdminComponent implements OnInit {
  @ViewChild('contact_dialog') contactDialog:any;
  public errorMessage = "";
  public successMessage = "";
  public admins: any[];
  public SERVER_URL: string = environment.serverUrl;
  public model: any = {message: "", id: -1};
  constructor(public lang: LanguageService, public router: Router, public appState: StateService, public generalService: GeneralService, public onePageService:OnePageService) {
    this.appState.set("one_page_menu_selected", 7);
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.appState.set("one_page_menu_selected", 1);
    this.generalService.isGeneralInfoFirstLoad = true;
    this.generalService.getAdminUsers().subscribe(result => {
       this.admins = result;
       this.appState.closeLoading();
     });
  }

  ngOnInit() {
  }

  ngOnDestroy() {

  }

  contact(id: number)
  {
    this.model.id = id;
    this.model.message = "";
    this.contactDialog.show();
  }

  tr(tran: string): string
  {
    return this.lang.tr("contact." + tran);
  }

}
