<?php
/*-------------------------------------------------------------------
* Purpose:
*         实现一个双向的链表
* Time:
*         2012年11月3日 14:01:23
* Author:
*         张彦升
--------------------------------------------------------------------*/

 //结点类
 class node { 
     
     public $data;
     public $next;
     
     function __construct($data) {
         $this->data = $data;
         $this->next = null;
     }
 }
 
 //链表类，链表中所有结点位置标号从1起始
 class linkList {
     
     //头结点
     private $header;
         
     function __construct () {
         $this->header = new node(null);
     }
     
     /**
      * 判断链表是否为空
      * @return bool
      */
     function isEmpty() {
         return ($this->header->next == null);
     }
     
     /**
      * 返回链表长度
      * @return int
      */
     function getLength() {
         $len = 0;
         $link = $this->header;
         while($link->next != null)
         {
             $len++;
             $link = $link->next;
         }
         return $len;
     }
     
     /**
      * 返回链表中值为$data的结点位置
      * @return array(找到结点)  false(未找到结点)
      */
     function find($data) {
         $index = array();
         $pos = 0;
         $link = $this->header;
         while($link != null)
         {
             if($link->data === $data) {
                 $index[] = $pos;
             }
             $pos++;
             $link = $link->next;
         }
         if(count($index) == 0) $index = false;
         return $index;
     }
     
     /**
      * 在链表第$index个位置插入值为$data的结点
      * @return bool
      */
     function insertAt($index, $data) {
         if(($index > $this->getLength()) || ($index < 1))
             return false;
         $link = $this->header;
         $pos = 0;
         while($index != $pos) {
             $prev = $link;
             $pos++;
             $link = $link->next;
         }
         $new = new node($data);
         $prev->next = $new;
         $new->next = $link; 
         return true;
     }
     
     /**
      * 在链表末尾添加值为$data的结点
      */
     function append($data) {
         $link = $this->header;
         while($link->next != null)
         {
             $link = $link->next;
         }
         $link->next = new node($data);        
     }
     
     /**
      * 删除链表第$index个结点
      * @return $deleted 删除结点的值
      */
     function deleteAt($index) {
         if(($index > $this->getLength()) || ($index < 1))
             return false;
         $link = $this->header;
         $pos = 0;
         while($pos != $index) {
             $prev = $link;
             $pos++;
             $link = $link->next;
         }
         $prev->next = $link->next;
         $deleted = $link->data;
         unset($link);
         return $deleted;
     }
     
     /**
      * 删除链表中所有值为$data的结点
      */
     function deleteIs($data) {
         $link = $this->header;
         while($link != null) {
             if($link->data !== $data) {
                 $prev = $link;
                 $link = $link->next;
             }
             else {
                 $prev->next = $link->next;
                 unset($link);
                 $link = $prev->next;
             }
         }
     }
     
     /**
      * 更新链表第$index个结点的值为$data
      * @return $updated 更新前的值
      */
     function updateAt($index, $data) {
         if(($index > $this->getLength()) || ($index < 1))
             return false;
         $link = $this->header;
         $pos = 0;
         while($pos != $index) {
             $pos++;
             $link = $link->next;
         }
         $updated = $link->data;
         $link->data = $data;
         return $updated;
     }
     
     /**
      * 将链表中所有值为$old的结点值替换为$new
      */
     function replace($old, $new) {
         $link = $this->header;
         while($link != null) {
             if($link->data === $old) {
                 $link->data = $new;
             }
             $link = $link->next;
         }
     }
     
     /**
      * 销毁链表
      */
     function destroy() {
         $this->header->next = null;
     }
     
     /**
      * 清空链表
      * 保留链表长度，令链表中每个结点为值为NULL
      */
     function truncate() {
         $link = $this->header;
         while($link != null)
         {
             $link->data = null;
             $link = $link->next;
         }
     }
 
     /**
      * 显示链表信息
      */
     function display() {
         if($this->isEmpty()) {
             echo "This is an empty linklist!\n";
             return;
         }
         
         $len = $this->getLength();
         if($len > 1)
             $msg = "There are {$len} elements in this linklist:\n";
         else
             $msg = "There is only 1 element in this linklist:\n";
         echo $msg;
         $i = 1;
         $link = $this->header;
         while($link->next != null) {
             $link = $link->next;
             printf("Data %02d is %s\n",$i,$link->data);
             $i++;
         }
     }
 
 } 
 ?>