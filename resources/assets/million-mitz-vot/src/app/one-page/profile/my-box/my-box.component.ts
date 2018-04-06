import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { GeneralService } from '../../../general.service';
import { AuthenticateService, USER_TYPE, USER_SIGNED_INFO } from '../../../authenticate.service';
import { ProfileService } from '../profile.service';
import { Box } from '../../..//model/box.type';

@Component({
  selector: 'app-my-box',
  templateUrl: 'my-box.component.html',
  styleUrls: ['my-box.component.css']
})
export class MyBoxComponent implements OnInit {
  @ViewChild('add_box_dialog') add_box_dialog: any;
  @ViewChild('edit_box_dialog') edit_box_dialog: any;
  public successMessage: string = "";
  public dialog_errorMessage: string = "";
  public device_id: number = -1;
  public dev_index: number = -1;
  public model: Box;
  public USER_TYPE = USER_TYPE;
  public USER_SIGNED_INFO: any = USER_SIGNED_INFO;
  public country_names = {
    CNY : "China",
    USD : "USA",
    EUR : "Europe",
    ILS : "Israel"
  };
  public country_codes = ["CNY", "USD", "EUR", "ILS"];
  constructor(public authService:AuthenticateService, public lang: LanguageService, public general: GeneralService, public router: Router, public appState: StateService, public profileService: ProfileService) {
    this.model = new Box();
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.getBoxes().subscribe(
     result => {
       if(!result)
         this.appState.errorMessage = this.tr("GET_FAILED");
       this.appState.closeLoading();
     });
  }
  createBox(addboxForm){
    console.log(addboxForm);
    let dev:any = this.model.device_id;
    if(!addboxForm.form.valid || parseInt(dev) <= 0)
    {
      this.appState.errorMessage = this.tr("FILL_ALL_ICON_FIELDS");
      return;
    }
    let box:Box = new Box();
    box.device_id = this.model.device_id;
    box.country_code = this.model.country_code;
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.addBox(this.model).subscribe(
     result => {
       if(result.success){
         this.add_box_dialog.hide();
       }
       else
         this.appState.errorMessage = result.message;
       this.appState.closeLoading();
     });
  }
  editBox(editboxForm){
    if(!editboxForm.form.valid)
    {
      this.appState.errorMessage = this.tr("FILL_ALL_ICON_FIELDS");
      return;
    }
    let box:Box = new Box();//this.profileService.boxes[this.dev_index];
    box.device_id = this.profileService.boxes[this.dev_index].device_id;
    box.country_code = this.model.country_code;
    box.major_version = this.model.major_version;
    box.minor_version = this.model.minor_version;
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.updateBox(this.model).subscribe(
     result => {
       if(result){
         this.profileService.boxes[this.dev_index].country_code = this.model.country_code;
         this.profileService.boxes[this.dev_index].major_version = this.model.major_version;
         this.profileService.boxes[this.dev_index].minor_version = this.model.minor_version;
         this.edit_box_dialog.hide();
       }
       else
         this.appState.errorMessage = this.tr("EDIT_BOX_FAILED");
       this.appState.closeLoading();
     });
  }
  removeBox(){
    let box:Box = this.profileService.boxes[this.dev_index];
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.removeBox(box).subscribe(
     result => {
       if(result){
         this.profileService.boxes.splice(this.dev_index, 1);
       }
       else
         this.appState.errorMessage = this.tr("REMOVE_BOX_FAILED");
       this.appState.closeLoading();
     });
  }
  resetBox(){
    let box:Box = this.profileService.boxes[this.dev_index];
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.resetBox(box).subscribe(
     result => {
       if(result){
         this.profileService.boxes[this.dev_index].deposit_amount = 0;
         this.successMessage = this.tr("RESET_SUCCESS");
       }
       else
         this.appState.errorMessage = this.tr("RESET_BOX_FAILED");
       this.appState.closeLoading();
     });
  }

  setModelData(box){
    this.model.device_id=box.device_id; 
    this.model.country_code = box.country_code;
    this.model.major_version = box.major_version;
    this.model.minor_version = box.minor_version;
  }
  
  updateFirmware(){
    let device_id = this.device_id;
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.updateFirmware(device_id).subscribe(
     result => {
       if(result){
         this.profileService.boxes[this.dev_index].update_flag = 1;
         this.successMessage = "Firmware update has been booked. Please restart your box to update firmware.";
         this.appState.errorMessage = "You need to restart your box to update firmware. It would take several minutes.";
       }
       else
         this.appState.errorMessage = this.tr("RESET_BOX_FAILED");
       this.appState.closeLoading();
     });
  }

  updateSound(){
    let device_id = this.device_id;
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.updateSound(device_id).subscribe(
     result => {
       if(result){
         this.profileService.boxes[this.dev_index].sound_update_flag = 1;
         this.successMessage = "Sound update has been booked. Please restart your box to update sound.";
         this.appState.errorMessage = "You need to restart your box to update sounds. It would take several minutes.";
       }
       else
         this.appState.errorMessage = this.tr("RESET_BOX_FAILED");
       this.appState.closeLoading();
     });
  }

  ngOnInit() {
  }

  tr(tran: string): string
  {
    return this.lang.tr("profile." + tran);
  }
}
