<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\InviteForm;

/**
 * InviteSearch represents the model behind the search form about `frontend\models\InviteForm`.
 */
class InviteSearch extends InviteForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sent_date', 'registration_date'], 'integer'],
            [['email', 'name', 'sex', 'location', 'status', 'role'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = InviteForm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'invite_by_user' => $this->invite_by_user,
            'sent_date' => $this->sent_date,
            'registration_date' => $this->registration_date,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['invite_by_user' => Yii::$app->user->identity->getId()]);

        return $dataProvider;
    }
}
