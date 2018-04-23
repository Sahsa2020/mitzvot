import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GuestMemberComponent } from './guest-member.component';

describe('GuestMemberComponent', () => {
  let component: GuestMemberComponent;
  let fixture: ComponentFixture<GuestMemberComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GuestMemberComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GuestMemberComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
