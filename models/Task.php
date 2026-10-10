<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $status
 * @property string $priority
 * @property string|null $due_date
 * @property int|null $category_id
 * @property string|null $created_at
 *
 * @property Categories $category
 */
class Task extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_TODO = 'todo';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DONE = 'done';
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'due_date', 'category_id'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'todo'],
            [['priority'], 'default', 'value' => 'medium'],
            [['title'], 'required'],
            [['description', 'status', 'priority'], 'string'],
            [['due_date', 'created_at'], 'safe'],
            [['category_id'], 'integer'],
            [['title'], 'string', 'max' => 150],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            ['priority', 'in', 'range' => array_keys(self::optsPriority())],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'priority' => 'Priority',
            'due_date' => 'Due Date',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_TODO => 'todo',
            self::STATUS_IN_PROGRESS => 'in_progress',
            self::STATUS_DONE => 'done',
        ];
    }

    /**
     * column priority ENUM value labels
     * @return string[]
     */
    public static function optsPriority()
    {
        return [
            self::PRIORITY_LOW => 'low',
            self::PRIORITY_MEDIUM => 'medium',
            self::PRIORITY_HIGH => 'high',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusTodo()
    {
        return $this->status === self::STATUS_TODO;
    }

    public function setStatusToTodo()
    {
        $this->status = self::STATUS_TODO;
    }

    /**
     * @return bool
     */
    public function isStatusInprogress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function setStatusToInprogress()
    {
        $this->status = self::STATUS_IN_PROGRESS;
    }

    /**
     * @return bool
     */
    public function isStatusDone()
    {
        return $this->status === self::STATUS_DONE;
    }

    public function setStatusToDone()
    {
        $this->status = self::STATUS_DONE;
    }

    /**
     * @return string
     */
    public function displayPriority()
    {
        return self::optsPriority()[$this->priority];
    }

    /**
     * @return bool
     */
    public function isPriorityLow()
    {
        return $this->priority === self::PRIORITY_LOW;
    }

    public function setPriorityToLow()
    {
        $this->priority = self::PRIORITY_LOW;
    }

    /**
     * @return bool
     */
    public function isPriorityMedium()
    {
        return $this->priority === self::PRIORITY_MEDIUM;
    }

    public function setPriorityToMedium()
    {
        $this->priority = self::PRIORITY_MEDIUM;
    }

    /**
     * @return bool
     */
    public function isPriorityHigh()
    {
        return $this->priority === self::PRIORITY_HIGH;
    }

    public function setPriorityToHigh()
    {
        $this->priority = self::PRIORITY_HIGH;
    }
}
