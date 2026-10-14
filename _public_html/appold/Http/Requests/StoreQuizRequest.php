<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // L'autorisation est gérée dans le contrôleur
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.text' => 'required|string',
            'questions.*.answers.*.is_correct' => 'required|boolean',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            foreach ($this->questions ?? [] as $index => $question) {
                // Vérifier qu'au moins une réponse est correcte
                $hasCorrectAnswer = collect($question['answers'] ?? [])->contains('is_correct', true);
                if (!$hasCorrectAnswer) {
                    $validator->errors()->add(
                        "questions.{$index}.answers",
                        "La question " . ($index + 1) . " doit avoir au moins une réponse correcte."
                    );
                }
                
                // Vérifier qu'il n'y a pas de réponses vides
                foreach ($question['answers'] ?? [] as $answerIndex => $answer) {
                    if (empty(trim($answer['text'] ?? ''))) {
                        $validator->errors()->add(
                            "questions.{$index}.answers.{$answerIndex}.text",
                            "La réponse " . ($answerIndex + 1) . " de la question " . ($index + 1) . " ne peut pas être vide."
                        );
                    }
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'questions.required' => 'Vous devez ajouter au moins une question.',
            'questions.min' => 'Vous devez ajouter au moins une question.',
            'questions.*.text.required' => 'Le texte de la question est requis.',
            'questions.*.answers.required' => 'Chaque question doit avoir au moins 2 réponses.',
            'questions.*.answers.min' => 'Chaque question doit avoir au moins 2 réponses.',
            'questions.*.answers.*.text.required' => 'Le texte de la réponse est requis.',
            'questions.*.answers.*.is_correct.required' => 'Vous devez indiquer si la réponse est correcte.',
            'passing_score.required' => 'Le score de passage est requis.',
            'passing_score.min' => 'Le score de passage doit être entre 0 et 100.',
            'passing_score.max' => 'Le score de passage doit être entre 0 et 100.',
        ];
    }
}

