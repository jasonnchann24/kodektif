<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionCommentRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_comment_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discussion_comment_id')->constrained('discussion_comments');
            $table->foreignId('user_id')->constrained('users');
            $table->longText('body');
            $table->json('mentions');
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discussion_comment_replies');
    }
}
