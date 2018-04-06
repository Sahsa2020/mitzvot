import { Component, OnInit, trigger, state, style, transition, animate, keyframes, AfterViewChecked, ElementRef, ViewChild} from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { GeneralService } from '../../../general.service';
import { OnePageService } from '../../one-page.service';
import { ProfileService } from '../profile.service';

import { Donate } from '../../../model/donate.type';

declare var jQuery:any;

@Component({
  selector: 'app-my-donate',
  templateUrl: './my-donate.component.html',
  styleUrls: ['./my-donate.component.css']
})
export class MyDonateComponent implements OnInit {
  @ViewChild('image_box_dialog') image_box_dialog: any;
  public successMessage = "";
  public model:Donate;
  public temp_image_origin: any;
  constructor(public lang: LanguageService, public router: Router, public appState: StateService, public generalService: GeneralService, public onePageService:OnePageService, public profileService: ProfileService, public elementRef: ElementRef) {
    this.model = new Donate();
    this.appState.setLoading('Loading ...');
    this.profileService.getDonate().subscribe(
     result => {
       if(result){
         this.model = profileService.myDonate;
         jQuery('#donate-image-viewer').attr('src', this.model.picture);
         jQuery('#cropper').cropper('replace', this.model.image_origin);
         jQuery('#cropper').cropper('crop');
       }
       else
         this.appState.errorMessage = 'Donate Load Error';
       this.appState.closeLoading();
     });
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
  saveDonate(contactForm){
    // var imageFile = jQuery(this.elementRef.nativeElement).find('.imageFile')[0];
    // if(!contactForm.form.valid)
    // {
    //   this.errorMessage = 'Please fill all required fields.';
    //   return;
    // }
    // if(imageFile.files.length != 0)
    // {
    //   this.model.picture = imageFile.files[0];
    // }
    this.model.image_origin = this.temp_image_origin;
    this.appState.setLoading('Saving...');
    this.profileService.saveDonate(this.model).subscribe(result => {
       if(result){
         this.successMessage = "Donate Successfully saved";
       }
       else
         this.appState.errorMessage = 'Donate Save Error';
       this.appState.closeLoading();
     },
     error=>{
       
     });
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
    }
    img.src = window.URL.createObjectURL(file);
  }

  saveImage(){
    let canvasData = jQuery('#cropper').cropper('getCroppedCanvas');
    // this.image_data = canvasData.toDataURL();
    this.model.picture = canvasData.toDataURL();
    let me = this;
    canvasData.toBlob(function(blob){
      me.model.pictureFile = me.blobToFile(blob, "image");
    }, "image/jpeg", 0.75);
    jQuery('#donate-image-viewer').attr('src', this.model.picture);
  }

  blobToFile(blob, name): any {
      blob.lastModifiedDate = new Date();
      blob.name = name;
      var file = new File([blob], name, {type: blob.type, lastModified: blob.lastModifiedDate});
      return file;
  }

}
