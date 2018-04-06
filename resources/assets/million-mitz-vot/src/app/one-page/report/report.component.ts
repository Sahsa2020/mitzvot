import { Component, OnInit, trigger, state, style, transition, animate, keyframes, AfterViewChecked, ViewChild} from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../language.service';
import { StateService } from '../../state.service';
import { GeneralService } from '../../general.service';
import { OnePageService } from '../one-page.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../authenticate.service';

declare var jQuery:any;
@Component({
  selector: 'app-report',
  templateUrl: './report.component.html',
  styleUrls: ['./report.component.css']
})
export class ReportComponent implements OnInit {
  @ViewChild('get_paid_dialog') get_paid_dialog: any;

  public successMessage = "";
  public avaliable_money:number = 0;

  public curPage:number = 1;
  public totalCount:number = 0;
  public itemsPerPage:number = 10;
  public transactions:any[];
  public transaction_type:string[] = ['', '', 'Member Paid', 'Withdraw', 'Fee'];

  public password:string="";
  public paid_amount:number=0;
  public errorPaidMessage:string="";
  // public model:ContactMessage;

  constructor(public lang: LanguageService, public router: Router, public appState: StateService, public authenticate: AuthenticateService, public generalService: GeneralService, public onePageService:OnePageService) {
    this.appState.set("one_page_menu_selected", 8);
    this.refreshTable({page: this.curPage});
  }

  refreshTable(event){
    this.appState.setLoading('Loading Transaction Histroy...');
    this.onePageService.getTransactionHistory().subscribe(
     result => {
       if(result.success)
       {
         console.log(result);
         this.avaliable_money = result.avaliable_money;
         var lastPageID = (this.curPage * this.itemsPerPage > result.invoiceData.length)?result.invoiceData.length:this.curPage * this.itemsPerPage;
         this.totalCount = result.invoiceData.length;
         this.transactions = [];
         for (var i = (this.curPage - 1) * this.itemsPerPage; i < lastPageID; i++) {
           this.transactions[this.transactions.length] = result.invoiceData[i];
         }
         console.log(this.transactions);
       }
       else
       {
         this.appState.errorMessage = "Transaction History Load Error";
       }
       this.appState.closeLoading();
     });
  }

  ngOnInit() {
  }

  initPaidData(){
    this.paid_amount = this.avaliable_money;
    this.password="";
  }

  getpaid(){

    if (this.paid_amount > this.avaliable_money) {
        this.errorPaidMessage = 'Inputed Amount is lager than avaliable Amount.';
        return;
    }
    this.appState.setLoading('Get Paid...');
    this.onePageService.getPaid(this.paid_amount, this.password).subscribe(
     result => {
       if(result)
       {
        this.successMessage = 'Get Paid Success';
        this.refreshTable({page: this.curPage});
        this.appState.closeLoading();
        this.get_paid_dialog.hide();
       }
       else
       {
         this.errorPaidMessage = "Get Paid Error. Please check your password";
         this.appState.closeLoading();
       }
     });
  }

}
