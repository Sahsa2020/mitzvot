import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { AuthenticateService } from '../../../authenticate.service';
import { ProfileService } from '../profile.service';
import { Member } from '../../..//model/member.type';

@Component({
  selector: 'app-my-member',
  templateUrl: 'my-member.component.html',
  styleUrls: ['my-member.component.css']
})
export class MyMemberComponent implements OnInit {
  @ViewChild('edit_member_dialog') edit_member_dialog: any;
  @ViewChild('add_member_dialog') add_member_dialog: any;
  public successMessage: string = "";
  public dialog_errorMessage: string = "";
  public model: Member;
  public dev_index: number = -1;
  public user_boxes: any[] = [];
  public country_names = {CNY : "China", USD : "USA", EUR : "Europe", ILS: "Israel"};
  public country_codes = ["CNY", "USD", "EUR", "ILS"];
  constructor(public authService:AuthenticateService, public lang: LanguageService, public router: Router, public appState: StateService, public profileService: ProfileService) {
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.model = new Member();
    this.profileService.getMembers().subscribe(
     result => {
       if(!result)
         this.appState.errorMessage = this.tr("GET_FAILED");
       this.appState.closeLoading();
     });
  }

  reloadMemberData(){
    this.profileService.getMembers().subscribe(
     result => {
       if(!result)
         this.appState.errorMessage = this.tr("GET_FAILED");
       this.appState.closeLoading();
     });
  }

  refreshBox(data){
    console.log("box data: ",data);
    let boxes: number[] = [];
    for(let i = 0; i < data.length; i ++){
      boxes.push(data[i].id);
    }
    this.model.boxes = boxes;
  }

  createMember(addmemberForm){
    if(!addmemberForm.form.valid)
    {
      this.appState.errorMessage = this.tr("FILL_ALL_ICON_FIELDS");
      return;
    }
    let member:Member = new Member();
    member.name = this.model.name;
    member.email = this.model.email;
    member.boxes = this.model.boxes;
    member.password = this.model.password;
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.addMember(this.model).subscribe(
     result => {
       if(result.success){
         this.reloadMemberData();
         this.add_member_dialog.hide();
       }
       else
         this.appState.errorMessage = result.message;
       this.appState.closeLoading();
     });
  }
  editMember(editmemberForm){
    if(!editmemberForm.form.valid)
    {
      this.appState.errorMessage = this.tr("FILL_ALL_ICON_FIELDS");
      return;
    }
    let member:Member = new Member();
    member.id = this.profileService.members[this.dev_index].id;
    member.name = this.model.name;
    member.email = this.model.email;
    member.boxes = this.model.boxes;
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.updateMember(this.model).subscribe(
     result => {
       if(result.success){
         this.profileService.members[this.dev_index].name = this.model.name;
         this.profileService.members[this.dev_index].email = this.model.email;
         this.edit_member_dialog.hide();
         this.reloadMemberData();
       }
       else
         this.appState.errorMessage = result.message;
       this.appState.closeLoading();
     });
  }
  removeMember(){
    let member:Member = this.profileService.members[this.dev_index];
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.removeMember(member).subscribe(
     result => {
       if(result){
         this.profileService.members.splice(this.dev_index, 1);
       }
       else
         this.appState.errorMessage = this.tr("REMOVE_MEMBER_FAILED");
       this.appState.closeLoading();
     });
  }

  array_to_string(array){
    let text = "";
    for(let i = 0; i < array.length; i++){
      text += array[i];
      if(i != array.length - 1)
        text += ", ";
    }
    return text;
  }

  ngOnInit() {
  }

  tr(tran: string): string
  {
    return this.lang.tr("profile." + tran);
  }

  showEditDialog(member: Member){
    member.secretCodes = new Array(member.boxes.length);
    member.secretCodes.fill("");
    this.model = JSON.parse(JSON.stringify(member));
    console.log(this.model);
    this.edit_member_dialog.show();
  }

  showAddDialog(){
    this.model = new Member();
    this.add_member_dialog.show();
  }

  customTrackBy(index: number, obj: any): any {
    return index;
  }

  addBoxToCreate(){
    this.model.boxes.push(0);
    this.model.secretCodes.push("");
  }

  removeBoxToCreate(){
    this.model.boxes.splice(this.model.boxes.length - 1, 1);
    this.model.secretCodes.splice(this.model.secretCodes.length - 1, 1);
  }
}
