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
import { TestComponent } from './test/test.component';
import { FooterComponent } from './shared/footer/footer.component';
import { HeaderComponent } from './shared/header/header.component';


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
    TestComponent,
    FooterComponent,
    HeaderComponent
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
