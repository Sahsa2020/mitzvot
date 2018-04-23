import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MyOrgMemberComponent } from './my-org-member.component';

describe('MyOrgMemberComponent', () => {
  let component: MyOrgMemberComponent;
  let fixture: ComponentFixture<MyOrgMemberComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MyOrgMemberComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MyOrgMemberComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
