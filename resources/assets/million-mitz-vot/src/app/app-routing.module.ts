import { NgModule }             from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { OnePageComponent, HomeComponent, AboutComponent, DirectorBoardComponent, ScoreBoardComponent, SellBoardComponent, SellDonateComponent, MySoundComponent, CheckoutComponent} from './one-page';
import { DonateComponent, ContactUsComponent, ContactAdminComponent, ReportComponent, ProfileComponent, MainInfoComponent, MyBoxComponent, MyMemberComponent, ChangePasswordComponent, MyDonateComponent, ActivateComponent } from './one-page';
import { Guard } from './guard';
import { AuthenticateService } from './authenticate.service';
// import { OnePageRoutes } from './one-page/one-page.routing';
import { SponsorComponent, SponsorListComponent, FaqComponent} from './one-page';
import { MainComponent, PersonalBoxComponent, PersonalSoundComponent, ChatComponent, EditProfileComponent, FriendComponent, PersonalReportComponent,
  GuestMemberComponent, MyOrgMemberComponent, NotificationComponent, PictureComponent, OrganizationComponent, PersonalAccountComponent } from './one-page/personal-account';

const routes: Routes = [
  {
    path: '',
    component: OnePageComponent,
    children: [
      { path: 'home',      component: HomeComponent },
      // { path: 'activateAccount',      component: HomeComponent },
      { path: 'about_us', component: AboutComponent},
      { path: 'director_board', component: DirectorBoardComponent },
      { path: 'score',      component: ScoreBoardComponent },
      { path: 'sell',       component: SellBoardComponent, },
      { path: 'selldonate',      component: SellDonateComponent },
      { path: 'donate',      component: DonateComponent },
      { path: 'contact',      component: ContactUsComponent },
      { path: 'activateAccount',      component: ActivateComponent },
      { path: 'contact-admin',      component: ContactAdminComponent },
      { path: 'report',      component: ReportComponent },
      { path: 'sponsor',      component:  SponsorComponent },
      { path: 'sponsor/list',      component: SponsorListComponent },
      { path: 'faq',      component: FaqComponent },
      {
        path: 'profile_org',
        component: ProfileComponent,
        children: [
          { path: '',      component: MainInfoComponent, },
          { path: 'boxes',      component: MyBoxComponent },
          { path: 'members',      component: MyMemberComponent },
          { path: 'sounds',      component: MySoundComponent },
          { path: 'changePassword',      component: ChangePasswordComponent },
          // { path: 'donate',      component: MyDonateComponent }
        ],
        canActivate: [ AuthenticateService ]
      },
      {
        path: 'profile',
        component: PersonalAccountComponent,
        children: [
          { path: '',      component: MainComponent, },
          { path: 'friends',      component: FriendComponent },
          { path: 'edit-profile',      component: EditProfileComponent },
          { path: 'boxes',      component: PersonalBoxComponent },
          { path: 'report',      component: PersonalReportComponent },
          { path: 'members',      component: MyOrgMemberComponent },
          { path: 'sounds',      component: PersonalSoundComponent },
          // { path: 'changePassword',      component: ChangePasswordComponent },
          // { path: 'donate',      component: MyDonateComponent }
        ],
        canActivate: [ AuthenticateService ]
      },
      {path: 'profile/donate',     component: MyDonateComponent},
      {
        path: 'personal-account',
        component: PersonalAccountComponent,
        children: [
          { path: '',      component: MainComponent, },
          { path: 'chat',      component: ChatComponent },
          { path: 'edit-profile',      component: EditProfileComponent },
          { path: 'friends',      component: FriendComponent },
          { path: 'notifications',      component: NotificationComponent },
          { path: 'organizations',      component: OrganizationComponent },
          { path: 'org-member-guest',      component: GuestMemberComponent },
          { path: 'org-member-myorg',      component: MyOrgMemberComponent },
          { path: 'picture',      component: PictureComponent },
        ],
        canActivate: [ AuthenticateService ]
      },
      { path: '**', redirectTo: 'home' }
    ]
  },
  { path: '**', redirectTo: '' },
];
@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: true })],
  exports: [RouterModule]
})
export class AppRoutingModule {}
