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
	trail: Trail = new Trail(null, null, null, null, null, null, null, null, null, null, null, null);
	trails: Trail[] = [];
	comments: Comment[] = [];
	trailId = this.route.snapshot.params.trailId;
	comment: Comment = new Comment(null, null, null, null, null, null);


	constructor(protected trailService: TrailService, protected commentService: CommentService, protected route: ActivatedRoute) {
	}

	ngOnInit() {

		this.trailService.getTrailByTrailId(this.trailId).subscribe(reply => this.trail = reply);
		var commentTrailId = this.commentService.getCommentByCommentTrailId(this.trailId);
		this.trailId.subscribe(comments => this.comments = comments);

	}
	// createComment() : any {
	// 	if(!this.event) {
	// 		return false
	// 	}
	//
	// 	let comment = new Comment(null, null,null, null, this.commentCreator.value.commentContent, null);
	//
	// 	this.commentService.createComment(comment)
	// 		.subscribe(status =>
	// 			this.status = status);
	// 			if(status.status === 200) {
	// 				this.Speak
	// 				this.createCommentForm.reset();
	// 			} else {
	// 				return false
	// 			}
	// 		})
	// }
	// 	speak() {
	//
	// 		let commentTrailId : string = this.route.snapshot.params["commentArtId"];
	// 	}
}