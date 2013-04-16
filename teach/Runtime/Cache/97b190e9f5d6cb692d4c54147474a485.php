<?php if (!defined('THINK_PATH')) exit();?><table width="600" class="dasha_table">
	<form id="approve_form_id" action="#" method="post">
	<tr>
		<td  width="90px" class="key">论文题目</td>
		<td colspan="3"><?php echo ($thesis['name']); ?></td>
		<td class="key">类别</td>
		<td><?php echo ($thesis['type']); ?></td>
	</tr>
	<tr>
		<td class="key">指导教师</td>
		<td><?php echo ($thesis['teacherid']); ?></td>
		<td class="key">针对专业</td>
		<td><?php echo ($thesis['grade']); ?>&nbsp;<?php echo ($thesis['majorid_name']); ?></td>
		<td class="key">学生层次</td>
		<td><?php echo ($thesis['level_name']); ?></td>
	</tr>

	<tr>
		<td class="key">题目内容与要求</td>
		<td colspan="5">
			<pre><?php echo ($thesis['content']); ?></pre>
		</td>
	</tr>
	<tr>
		<td class="key">评审评语</td>
		<td colspan="5">
			<input type="hidden" name="thesisid" value="<?php echo ($thesis['thesisid']); ?>">
			<textarea name="comment" id="comment" rows="10" cols="40" validate="required:true, minlength:2, maxlength:1024"><?php echo ($thesis['comment']); ?></textarea>
		</td>
	</tr>
	</form>
</table>