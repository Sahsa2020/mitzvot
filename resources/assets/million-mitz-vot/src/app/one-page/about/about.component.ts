import { Component, OnInit, trigger, state, style, transition, animate, keyframes, AfterViewChecked} from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { AuthenticateService } from '../../authenticate.service';
import { OnePageService } from '../one-page.service';
import { ContactMessage } from '../../model/contact_message.type';

declare var jQuery:any;
@Component({
  selector: 'app-about',
  templateUrl: 'about.component.html',
  styleUrls: ['about.component.css']
})
export class AboutComponent implements OnInit {
  public directors: any[];
  public errorMessage = "";
  public successMessage = "";
  public model:ContactMessage;
  constructor(public lang: LanguageService, public router: Router, public appState: StateService, public generalService: GeneralService, public onePageService: OnePageService) {
    this.appState.set("one_page_menu_selected", 4);
    this.directors = [
      {
        name: "Mr. Bruce J. Schanzer",
        description: "Mr. Bruce J. Schanzer has been president, chief executive officer and a director of Cedar Realty Trust Inc (CDR) since June 2011. Prior thereto and since 2007, Mr. Schanzer was employed by Goldman Sachs & Co., with his last position being a managing director in their real estate investment banking group. From 2001 to 2007, Mr. Schanzer was employed by Merrill Lynch, with his last position being vice president in their real estate investment banking group. Earlier in his career, Mr. Schanzer practiced real estate law for six years in New York. Mr. Schanzer received a B.A. from Yeshiva College, where he is now a member of its board of trustees, an M.B.A. from the University of Chicago, and a J.D. from Benjamin N. Cardozo School of Law, where he was a member of the Law Review.",
        image_url: "assets/img/director.png"
      },
      {
        name: "Yaron Pinchas",
        description: "Yaron Pinchas. Born and raised in Israel and received a  Bachelor Of Arts in Economics from the University of Haifa, Israel. Yaron served in the IDF for three years. He is the founder and director of a Construction Company in Nigeria, a founder and managing director of a communication company in Nigeria, director and partner in agriculture companies in Central America and the U.S.A.",
        image_url: "assets/img/director_2.jpg"
      }
    ];
    this.model = new ContactMessage();
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
       }
       else
       {
         this.errorMessage = this.tr("SENT_FAILED");//"Please check your email and password again.";
       }
       this.appState.closeLoading();
     });
  }

  ngOnInit() {
  }

  ngOnDestroy() {

  }
  tr(tran: string): string
  {
    return this.lang.tr("about." + tran);
  }
}
