import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { AuthenticateService, USER_TYPE, USER_SIGNED_INFO } from '../../../authenticate.service';
import { ProfileService } from '../profile.service';
import { RegisterUser } from '../../..//model/user_register.type';
// import { ImageCropperComponent, CropperSettings } from 'ng2-img-cropper';

declare var jQuery:any;

@Component({
  selector: 'app-main-info',
  templateUrl: 'main-info.component.html',
  styleUrls: ['main-info.component.css']
})
export class MainInfoComponent implements OnInit {
  @ViewChild('image_box_dialog') image_box_dialog: any;
    // cropper:ImageCropperComponent;
  public image_data:any = null;
  public successMessage: string = "";
  // public cropperSettings: CropperSettings;
  public temp_image_origin: any = null;
  public temp_image_data: any;
  public model: any = {};
  public USER_TYPE: any = USER_TYPE;
  public USER_SIGNED_INFO: any = USER_SIGNED_INFO;
  constructor(public authService:AuthenticateService, public lang: LanguageService, public router: Router, public appState: StateService, public profileService: ProfileService) {
   if(this.authService.isLoggedIn() == USER_SIGNED_INFO.SIGNED_IN){
      this.model = JSON.parse(JSON.stringify(this.authService.currentUser));
      jQuery('#profile-image-viewer').attr('src', this.model.image_url);
   }
   else{
     this.authService.get('/api/v1/profile').subscribe(
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
   this.temp_image_data = {};
  }

  getYoutubeId(url) {
      let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
      let match = url.match(regExp);

      if (match && match[2].length == 11) {
          return match[2];
      } else {
          return 'error';
      }
  }

  checkVideoURL(): boolean{
      if(!this.model.weekly_mail_video)
          return false;
      let provider1, provider2;
      try{
          provider1 = this.model.weekly_mail_video.match(/http:\/\/(:?www.)?(\w*)/)?this.model.weekly_mail_video.match(/http:\/\/(:?www.)?(\w*)/)[2]: null;
          provider2 = this.model.weekly_mail_video.match(/https:\/\/(:?www.)?(\w*)/)?this.model.weekly_mail_video.match(/https:\/\/(:?www.)?(\w*)/)[2]: null;
      }
      catch(e){
          console.log(e);
      }
      if(provider1 == "youtube" || provider2 == "youtube"){
          this.model.weekly_mail_video = "https://www.youtube.com/embed/" + this.getYoutubeId(this.model.weekly_mail_video);
          return true;
      }
      if(provider1 == "vimeo" || provider2 == "vimeo")
          return true;
      return false;
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
      me.appState.setLoading(me.tr("LOADING_TEXT"));
      me.model.image_origin = me.temp_image_origin;
      me.profileService.updateProfileImage(me.model).subscribe(
        (result: any) => {
          if(result.success)
          {
            me.successMessage = "Your Profile Image has been uploaded successfully.";
            me.model.image_url = result.data.image_url;
            me.authService.validateToken();
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
         this.authService.validateToken();
       }
       else
       {
         this.appState.errorMessage = this.tr("UPDATE_FAILED");//"Please check your email and password again.";
       }
       this.appState.closeLoading();
     });
  }
  ngOnInit() {
    if (jQuery().datepicker) {
        jQuery('.date-picker').datepicker({
            rtl: false,
            orientation: "left",
            autoclose: true
        });
    }
    if(this.authService.isLoggedIn() == USER_SIGNED_INFO.SIGNED_IN){
      jQuery('#profile-image-viewer').attr('src', this.model.image_url);
      jQuery('#input-birthday').val(this.model.birthday);
    }
    let me = this;
    jQuery('#cropper').cropper({
      aspectRatio: 1,
      // preview: '#crop-preview-pan',
      cropend: function(e){
        // let canvasData = jQuery('#cropper').cropper('getCroppedCanvas');
        // delete me.temp_image_data.image;
        // me.temp_image_data.image = canvasData.toDataURL();
        // jQuery('#crop-preview-pan').attr('src', me.temp_image_data.image);
      },
      minContainerWidth: 400,
      minContainerHeight: 400,
      ready: function (e) {
        jQuery('#cropper').cropper('crop');
      }
    });
    if(this.model.image_origin != null && this.model.image_origin != ''){
      jQuery('#cropper').cropper('replace', this.model.image_origin);
      jQuery('#cropper').cropper('crop');
    }
  }
  tr(tran: string): string
  {
    return this.lang.tr("profile." + tran);
  }
}
