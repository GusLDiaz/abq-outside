import {Component, OnInit} from "@angular/core";
import {Status} from "./shared/classes/status";
import {SessionService} from "./shared/services/session.service";
import {TrailService} from "./shared/services/trail.service";
import {Trail} from "./shared/classes/trail";
import {CommentService} from "./shared/services/comment.service";
import {Comment} from "./shared/classes/comment";

@Component({
	selector: "abq-outside",
	template: require("./app.component.html")
})
export class AppComponent implements OnInit{

	trail : string = "";
	trails : Trail[];
	//comments : Comment[] = [];
	status : Status = null;

	 constructor(protected trailService: TrailService) { //}, protected commentService: CommentService) {
	 // 	this.sessionService.setSession()
	 // 		.subscribe(status => this.status = status);
	  }
	  ngOnInit() : void {
	 	this.trailService.getAllTrails()
	 		.subscribe(trails => this.trails = trails);
	 	//this.trail = this.trails.trailId
		  //gather comments from trailId called
	 	//this.commentService.getCommentByCommentTrailId(this.trails.trailId)
			//.subscribe(comments => this.comments = comments);
	  }
}