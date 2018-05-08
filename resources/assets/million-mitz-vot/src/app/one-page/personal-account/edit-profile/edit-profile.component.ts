import { Component, OnInit, ViewChild} from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { GeneralService } from '../../../general.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../../authenticate.service';
import { environment } from '../../../../environments';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Location } from '@angular/common';
import { RegisterUser } from '../../../model/user_register.type';
import { ProfileService } from '../../';

declare var jQuery:any;

@Component({
  selector: 'app-edit-profile',
  templateUrl: './edit-profile.component.html',
  styleUrls: ['./edit-profile.component.css']
})


export class EditProfileComponent implements OnInit {

  @ViewChild('image_box_dialog') image_box_dialog: any;

  public menus = [
    {'id': 1, 'name':'Personal Details'},
    {'id': 2, 'name':'Bank Details'},
    {'id': 3, 'name':'Your Goals'},
    {'id': 4, 'name':'Change Password'},
    {'id': 5, 'name':'Weekly Digest Email'},
    // {'id': 6, 'name':'My Socials'},
    // {'id': 7, 'name':'Confidential Settings'},
    {'id': 8, 'name':'Delete account'},
  ];
  public menu_id = 1;
  public current_user:any;
  public personal = {
    'type': '',
    'phone': '',
    'email': '',
    'address': '',
    'city': '',
    'country': '',
    'birthday': '',
    'name': '',
    'bank_account': '',
    'routing_number': '',
    'account_number': '',
    'name_of_bank_account': '',
    'bank_name': '',
    'account_type': '',
    'goal_daily':'',
    'goal_weekly':'',
    'goal_monthly':'',
    'cur_password':'',
    'new_password':'',
    'confirm_password':'',
    'weekly_mail_video':'',
    'del_flg':''
  }

  private location: Location;
  public model:any = {};
  public temp_image_origin:any;

  public image_data:any = null;
  public successMessage: string = "";
  public USER_SIGNED_INFO: any = USER_SIGNED_INFO;
  public USER_TYPE: any = USER_TYPE;
  constructor(public lang: LanguageService, public profileService:ProfileService, public general: GeneralService, public router: Router, public appState: StateService, public authenticate: AuthenticateService, ) {
    this.authenticate.validateToken();
    if(this.authenticate.isLoggedIn() == USER_SIGNED_INFO.SIGNED_IN){
      this.model = JSON.parse(JSON.stringify(this.authenticate.currentUser));
      jQuery('#profile-image-viewer').attr('src', this.model.image_url);
   }
   else{
     this.authenticate.get('/api/v1/profile').subscribe(
        (res: any) => {
              if(res.success){
                this.model = res.data;
                jQuery('#input-birthday').val(this.model.birthday);
                console.log(this.model);
                jQuery('#profile-image-viewer').attr('src', this.model.image_url);
                jQuery('#cropper').cropper('replace', this.model.image_origin);
                jQuery('#cropper').cropper('crop');
              }
        },
        error => {
          this.router.navigate(['/home']);
        }
      );
   }
  }

  ngOnInit() {
    jQuery('#cropper').cropper({
      aspectRatio: 1,
      minContainerWidth: 400,
      minContainerHeight: 400,
      ready: function (e) {
        jQuery('#cropper').cropper('crop');
      }
    });
  }

  onMenu(menu) {
    this.menu_id = menu.id;
  }

  onChange(event) {
    console.log(event);
    
  }

  onInit() {
    this.personal.phone = this.authenticate.currentUser.phone;
    this.personal.email= this.authenticate.currentUser.email;
    this.personal.address = this.authenticate.currentUser.address;
    this.personal.city = this.authenticate.currentUser.city;
    this.personal.country = this.authenticate.currentUser.country;
    this.personal.birthday = this.authenticate.currentUser.birthday;
    this.personal.name = this.authenticate.currentUser.name;

    this.personal.bank_account = this.authenticate.currentUser.bank_account;
    this.personal.routing_number = this.authenticate.currentUser.routing_number;
    this.personal.account_number = this.authenticate.currentUser.account_number;
    this.personal.name_of_bank_account = this.authenticate.currentUser.name_of_bank_account;
    this.personal.bank_name = this.authenticate.currentUser.bank_name;
    this.personal.account_type = this.authenticate.currentUser.account_type;

    this.personal.goal_daily = this.authenticate.currentUser.goal_daily;
    this.personal.goal_weekly = this.authenticate.currentUser.goal_weekly;
    this.personal.goal_monthly = this.authenticate.currentUser.goal_monthly;

    this.personal.weekly_mail_video = this.authenticate.currentUser.weekly_mail_video;

  }

  fileChange(input){
    if(input.files.length < 1)
      return;
    if(input.files[0].size > 4 * 1024 * 1024)
    {
      this.appState.errorMessage = "You can't upload image which is larger than 4MB.";
      this.image_box_dialog.hide();
      return;
    }
    let file:File = input.files[0];
    this.temp_image_origin = file;
    var img = new Image;
    img.onload = function()
    {
        jQuery('#cropper').cropper('replace', img.src);
        jQuery('#cropper').cropper('crop');
        // .cropper('clear');
        // window.URL.revokeObjectURL(img.src);
    }
    img.src = window.URL.createObjectURL(file);
  }

  saveImage(){
    let canvasData = jQuery('#cropper').cropper('getCroppedCanvas');
    // this.image_data = canvasData.toDataURL();
    this.model.image_url = canvasData.toDataURL();
    let me = this;
    canvasData.toBlob(function(blob){
      me.model.image = me.blobToFile(blob, "image");
      me.appState.setLoading('Loading...');
      me.model.image_origin = me.temp_image_origin;
      me.profileService.updateProfileImage(me.model).subscribe(
        (result: any) => {
          if(result.success)
          {
            me.successMessage = "Your Profile Image has been uploaded successfully.";
            me.model.image_url = result.data.image_url;
            me.authenticate.validateToken();
          }
          else
          {
            me.appState.errorMessage = "Please check your network status.";
          }
          me.appState.closeLoading();
      });
    }, "image/jpeg", 0.75);
    jQuery('#profile-image-viewer').attr('src', this.model.image_url);
  }

  blobToFile(blob, name): any {
    blob.lastModifiedDate = new Date();
    blob.name = name;
    var file = new File([blob], name, {type: blob.type, lastModified: blob.lastModifiedDate});
    return file;
};

  onUpdatePersonal(profileForm) {

    // console.log(this.personal);
    // this.general.updatePersonalDetails(this.personal).subscribe(
    //   res => {
    //     console.log(res);
    //   },
    //   error =>{
    //   }
    // );

    this.model.birthday = jQuery('#input-birthday').val();
    if(!profileForm.form.valid || this.model.birthday == "")
    {
      this.appState.errorMessage = this.tr("FILL_ALL_REQUIRE_FIELDS");
      return;
    }
    // this.model.image = this.image_data;
    // if(this.image_data != null && this.image_data.src != this.model.image_origin){
    //   this.model.image_origin = this.temp_image_origin;
    // }
    this.appState.setLoading('Loading...');
    this.general.updatePersonalDetails(this.model).subscribe(
     (result: any) => {
       if(result.success)
       {
        //  this.successMessage = this.tr("SUCCESS_MESSAGE");
        this.successMessage = 'Updated Successfully';        
        //  this.model.image_url = result.data.image_url;
         this.authenticate.validateToken();
       }
       else
       {
         this.appState.errorMessage = 'Updated Failed';//"Please check your email and password again.";
       }
       this.appState.closeLoading();
    });
  }

  onUpdateGoals(profileForm) {
    // console.log(this.personal);
    // this.general.updateGoals(this.personal).subscribe(
    //   res => {
    //     console.log(res);
    //   },
    //   error =>{
    //   }
    // );

    
    if(!profileForm.form.valid)
    {
      this.appState.errorMessage = this.tr("FILL_ALL_REQUIRE_FIELDS");
      return;
    }
    
    this.appState.setLoading('Loading...');
    this.general.updateGoals(this.model).subscribe(
      (result: any) => {
        if(result.success)
        {
        //  this.successMessage = this.tr("SUCCESS_MESSAGE");
        this.successMessage = 'Updated Successfully';
        //  this.model.image_url = result.data.image_url;
          this.authenticate.validateToken();
        }
        else
        {
          this.appState.errorMessage = 'Updated Failed';//"Please check your email and password again.";
        }
        this.appState.closeLoading();
    });
  }

  onChangePassword(profileForm) {

    if(!profileForm.form.valid)
    {
      this.appState.errorMessage = this.tr("FILL_ALL_REQUIRE_FIELDS");
      return;
    }
    
    // this.appState.setLoading('Loading...');

    // console.log(this.personal);
    // this.general.updatePassword(this.personal).subscribe(
    //   res => {
    //     console.log(res);
    //   },
    //   error =>{
    //   }
    // );

    this.appState.setLoading('Loading...');
    this.general.updatePassword(this.personal).subscribe(
      (result: any) => {
        if(result.success)
        {
        //  this.successMessage = this.tr("SUCCESS_MESSAGE");
        this.successMessage = 'Updated Successfully';
        //  this.model.image_url = result.data.image_url;
          this.authenticate.validateToken();
        }
        else
        {
          this.appState.errorMessage = 'Updated Failed';//"Please check your email and password again.";
        }
        this.appState.closeLoading();
    });
  }

  onUpdateVideo() {
    console.log(this.personal);
    this.general.updateVideo(this.personal).subscribe(
      res => {
        console.log(res);
      },
      error =>{
      }
    );
  }

  onUpdateBank(profileForm) {
    // console.log(this.personal);
    // this.general.updateBankDetails(this.personal).subscribe(
    //   res => {
    //     console.log(res);
    //   },
    //   error =>{
    //   }
    // );

    console.log(profileForm);

    if(!profileForm.form.valid)
    {
      this.appState.errorMessage = this.tr("FILL_ALL_REQUIRE_FIELDS");
      return;
    }
   
    this.appState.setLoading('Loading...');
    this.general.updateBankDetails(this.model).subscribe(
     (result: any) => {
       if(result.success)
       {
        //  this.successMessage = this.tr("SUCCESS_MESSAGE");
        this.successMessage = 'Updated Successfully';
        //  this.model.image_url = result.data.image_url;
         this.authenticate.validateToken();
       }
       else
       {
         this.appState.errorMessage = 'Updated Failed';//"Please check your email and password again.";
       }
       this.appState.closeLoading();
    });
  }

  onDeleteAccount() {
    this.general.deleteAccount().subscribe(
      res => {
        console.log(res);
      },
      error =>{
      }
    );
  }

  updateProfile(profileForm){
    this.model.birthday = jQuery('#input-birthday').val();
    if(!profileForm.form.valid || this.model.birthday == "")
    {
      this.appState.errorMessage = this.tr("FILL_ALL_REQUIRE_FIELDS");
      return;
    }
    // this.model.image = this.image_data;
    // if(this.image_data != null && this.image_data.src != this.model.image_origin){
    //   this.model.image_origin = this.temp_image_origin;
    // }
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.updateProfile(this.model).subscribe(
     (result: any) => {
       if(result.success)
       {
         this.successMessage = this.tr("SUCCESS_MESSAGE");
         this.model.image_url = result.data.image_url;
         this.authenticate.validateToken();
       }
       else
       {
         this.appState.errorMessage = this.tr("UPDATE_FAILED");//"Please check your email and password again.";
       }
       this.appState.closeLoading();
     });
  }

  tr(tran: string): string
  {
    return this.lang.tr("profile." + tran);
  }

}
