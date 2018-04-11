import { Component, OnInit, trigger, state, style, transition, animate, keyframes, AfterViewChecked} from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { OnePageService } from '../one-page.service';
import { AuthenticateService, USER_SIGNED_INFO } from '../../authenticate.service';
import { ContactMessage } from '../../model/contact_message.type';

declare var jQuery:any;

@Component({
  selector: 'app-contact-us',
  templateUrl: './contact-us.component.html',
  styleUrls: ['./contact-us.component.css']
})
export class ContactUsComponent implements OnInit {
  public errorMessage = "";
  public successMessage = "";
  public model:ContactMessage;
  public USER_SIGNED_INFO = USER_SIGNED_INFO;
  constructor(public lang: LanguageService, public router: Router, public appState: StateService, public authService: AuthenticateService, public generalService: GeneralService, public onePageService:OnePageService) {
    this.appState.set("one_page_menu_selected", 12);
    this.model = new ContactMessage();
  }

  ngOnInit() {
    this.appState.set('is_on_contact_us', true);
  }

  ngOnDestroy() {
    this.appState.set('is_on_contact_us', false);
  }

  sendContactMessage(contactForm){
    if(!contactForm.form.valid)
    {
      this.errorMessage = this.tr("FILL_ALL_REQUIRE_FIELDS");
      return;
    }
    console.log(this.model);
    // this.model.image = this.image_data.image;
    // this.model.birthday = jQuery('#input-birthday').val();
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.onePageService.sendContactMessage(this.model).subscribe(
     result => {
       if(result)
       {
         this.successMessage = this.tr("SUCCESS_MESSAGE");
         this.errorMessage = "";
         this.model = new ContactMessage();
       }
       else
       {
         this.errorMessage = this.tr("SENT_FAILED");//"Please check your email and password again.";
       }
       this.appState.closeLoading();
     });
  }

  openChatWindow(){
    this.appState.set('is_chat_window_open', true);
  }

  tr(tran: string): string
  {
    return this.lang.tr("contact." + tran);
  }

}
