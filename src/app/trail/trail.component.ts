import {Component, OnInit} from "@angular/core"
import {TrailService} from "../shared/services/trail.service";
import {Trail} from "../shared/classes/trail";
import {CommentService} from "../shared/services/comment.service";
import {Comment} from "../shared/classes/comment";
import {ActivatedRoute} from "@angular/router";
@Component({
	template: require("./trail.component.html")
})
export class TrailComponent implements OnInit {
	trail : Trail = new Trail(null,"lol",null,null,null,null,null,null,null,null,null,null);
	trails : Trail[] = [];
	comments : Comment[] = [];
	constructor(protected trailService: TrailService, protected commentService: CommentService, protected route : ActivatedRoute) {
	}
	ngOnInit() {
		this.trailService.getTrailByTrailId(this.route.snapshot["trailId"]).subscribe(reply => this.trail = reply);
		//this.trailService.getAllTrails().subscribe(trail => this.trails = trails)
		//this.trail = trail.trailId;
		//this.commentService
	}
}