import { NgModule }             from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { OnePageComponent, HomeComponent, AboutComponent, DirectorBoardComponent, ScoreBoardComponent, SellBoardComponent, SellDonateComponent, MySoundComponent} from './one-page';
import { DonateComponent, ContactUsComponent, ContactAdminComponent, ReportComponent, ProfileComponent, MainInfoComponent, MyBoxComponent, MyMemberComponent, ChangePasswordComponent, MyDonateComponent, ActivateComponent } from './one-page';
import { Guard } from './guard';
import { AuthenticateService } from './authenticate.service';
// import { OnePageRoutes } from './one-page/one-page.routing';
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
      { path: 'sell',      component: SellBoardComponent },
      { path: 'selldonate',      component: SellDonateComponent },
      { path: 'donate',      component: DonateComponent },
      { path: 'contact',      component: ContactUsComponent },
      { path: 'activateAccount',      component: ActivateComponent },
      { path: 'contact-admin',      component: ContactAdminComponent },
      { path: 'report',      component: ReportComponent },
      {
        path: 'profile',
        component: ProfileComponent,
        children: [
          { path: '',      component: MainInfoComponent, },
          { path: 'boxes',      component: MyBoxComponent },
          { path: 'members',      component: MyMemberComponent },
          { path: 'sounds',      component: MySoundComponent },
          { path: 'changePassword',      component: ChangePasswordComponent },
          { path: 'donate',      component: MyDonateComponent }
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
