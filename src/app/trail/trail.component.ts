import {Component, OnInit} from "@angular/core"
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {TrailService} from "../shared/services/trail.service";
import {Trail} from "../shared/classes/trail";
import {CommentService} from "../shared/services/comment.service";
import {Comment} from "../shared/classes/comment";
import {ActivatedRoute} from "@angular/router";
import {Status} from "../shared/classes/status";

@Component({
	template: require("./trail.component.html")
})
export class TrailComponent implements OnInit {
	trail: Trail = new Trail(null, null, null, null, null, null, null, null, null, null, null, null);
	trails: Trail[] = [];
	comments: Comment[] = [];
	trailId = this.route.snapshot.params.trailId;
	comment: Comment = new Comment(null, null, null, null, null, null);
	commentCreator: FormGroup;
	status: Status = null;

	constructor(protected trailService: TrailService, protected formBuilder: FormBuilder, protected commentService: CommentService, protected route: ActivatedRoute) {
	}

	ngOnInit() {
		// showComments : void {
		//
		// }
		this.trailService.getTrailByTrailId(this.trailId).subscribe(reply => this.trail = reply);
		let commentTrailId = this.commentService.getCommentByCommentTrailId(this.trailId);
		commentTrailId.subscribe(comments => this.comments = comments);
		this.commentCreator = this.formBuilder.group({
			commentContent: ["", [Validators.maxLength(2000), Validators.required]]
		});
	}

	createComment(): any {

		let comment = new Comment(null, null, null, null, this.createComment().value.commentContent, null);

		this.commentService.createComment(comment)
			.subscribe(status =>
				this.status = status);
		// if(status.status === 200) {
		// 	this.showComments();
		// 	this.createComment.reset();
		// } else {
		// 	return false
		// }
	}
}