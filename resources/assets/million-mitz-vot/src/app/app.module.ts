import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http'; 
import { AppRoutingModule}  from './app-routing.module';
import { AppComponent } from './app.component';
import { OnePageComponent, HomeComponent, AboutComponent, DirectorBoardComponent, ScoreBoardComponent, SellBoardComponent } from './one-page';
import { DonateComponent, ContactUsComponent, ContactAdminComponent, ReportComponent, ProfileComponent, MainInfoComponent } from './one-page';
import { MyBoxComponent, MyMemberComponent, ChangePasswordComponent, MyDonateComponent, ActivateComponent, SellDonateComponent, MySoundComponent } from './one-page';
import { AlertModule, DatepickerModule, PaginationModule, ModalModule, BsDropdownModule  } from 'ngx-bootstrap';
import { AuthenticateService } from './authenticate.service';
import { LanguageService } from './language.service';
import { GeneralService } from './general.service';
import { StateService } from './state.service';
import { OnePageService } from './one-page/one-page.service';
import { ProfileService } from './one-page/profile/profile.service';
import { Guard } from './guard';
import { FooterComponent } from './shared/footer/footer.component';
import { HeaderComponent } from './shared/header/header.component';
import { CheckoutComponent } from './one-page/sell-board/checkout/checkout.component';
import { SponsorComponent } from './one-page/sponsor/sponsor.component';
import { SponsorListComponent } from './one-page/sponsor/sponsor-list/sponsor-list.component';
import { FaqComponent } from './one-page/faq/faq.component';
import { PersonalAccountComponent } from './one-page/personal-account/personal-account.component';
import { ChatComponent } from './one-page/personal-account/chat/chat.component';
import { EditProfileComponent } from './one-page/personal-account/edit-profile/edit-profile.component';
import { FriendComponent } from './one-page/personal-account/friend/friend.component';
import { NotificationComponent } from './one-page/personal-account/notification/notification.component';
import { OrganizationComponent } from './one-page/personal-account/organization/organization.component';
import { GuestMemberComponent } from './one-page/personal-account/guest-member/guest-member.component';
import { PictureComponent } from './one-page/personal-account/picture/picture.component';
import { MyOrgMemberComponent } from './one-page/personal-account/my-org-member/my-org-member.component';
import { MainComponent } from './one-page/personal-account/main/main.component';
import { PersonalBoxComponent } from './one-page/personal-account/personal-box/personal-box.component';
import { PersonalSoundComponent } from './one-page/personal-account/personal-sound/personal-sound.component';
import { PersonalReportComponent } from './one-page/personal-account/personal-report/personal-report.component';



@NgModule({
  imports: [
    BrowserModule,
    FormsModule,
    HttpClientModule,
    AppRoutingModule,
    DatepickerModule.forRoot(),
    AlertModule.forRoot(),
    PaginationModule.forRoot(),
    ModalModule.forRoot(),
    BsDropdownModule.forRoot(),
  ],
  declarations: [
    AppComponent,
    OnePageComponent,
    HomeComponent,
    AboutComponent,
    DirectorBoardComponent,
    ScoreBoardComponent,
    SellBoardComponent,
    SellDonateComponent,
    DonateComponent,
    ContactUsComponent,
    ContactAdminComponent,
    ReportComponent,
    ProfileComponent,
    MainInfoComponent,
    MyBoxComponent,
    MyMemberComponent,
    ChangePasswordComponent,
    MyDonateComponent,
    ActivateComponent,
    MySoundComponent,
    FooterComponent,
    HeaderComponent,
    CheckoutComponent,
    SponsorComponent,
    SponsorListComponent,
    FaqComponent,
    PersonalAccountComponent,
    ChatComponent,
    EditProfileComponent,
    FriendComponent,
    NotificationComponent,
    OrganizationComponent,
    GuestMemberComponent,
    PictureComponent,
    MyOrgMemberComponent,
    MainComponent,
    PersonalBoxComponent,
    PersonalSoundComponent,
    PersonalReportComponent,    
  ],
  providers: [
    AuthenticateService,
    LanguageService,
    GeneralService,
    StateService,
    OnePageService,
    ProfileService,
    Guard
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
